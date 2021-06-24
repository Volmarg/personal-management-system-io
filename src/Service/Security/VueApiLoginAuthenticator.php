<?php

namespace App\Service\Security;

use App\Controller\Core\Form;
use App\Controller\Core\Services;
use App\Controller\UserController;
use App\DTO\BaseApiDTO;
use App\DTO\Internal\Form\Security\LoginFormDataDTO;
use App\Service\SessionService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * Handles the authentication for API called by VUE
 * @link https://symfony.com/doc/current/security/guard_authentication.html#the-guard-authenticator-methods
 *
 * Class VueApiLoginAuthenticator
 * @package App\Service\Security
 */
class VueApiLoginAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var Form $form
     */
    private Form $form;

    /**
     * @var UserController $userController
     */
    private UserController $userController;

    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * Must be set to class property as authentication is strictly bound to the interface which already defines method parameters
     * This is used to check the csrf token in incoming request
     *
     * @var Request $request
     */
    private Request $request;

    /**
     * @var ParameterBagInterface $parameterBag
     */
    private ParameterBagInterface $parameterBag;

    public function __construct(
        EntityManagerInterface $em,
        Services $services,
        Form $form,
        UserController $userController,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage,
        ParameterBagInterface $parameterBag
    )
    {
        $this->userController = $userController;
        $this->parameterBag   = $parameterBag;
        $this->tokenStorage   = $tokenStorage;
        $this->urlGenerator   = $urlGenerator;
        $this->services       = $services;
        $this->form           = $form;
        $this->em             = $em;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        // check if is logged in, if yes then support = false
        if(
                $request->getMethod()     === Request::METHOD_POST
            &&  $request->getRequestUri() === $this->urlGenerator->generate("login")
        ){
            $this->request = $request;
            return true;
        }
        return false;
    }

    /**
     * @param UserInterface $user
     * @param string $providerKey
     * @return PostAuthenticationGuardToken
     */
    public function createAuthenticatedToken(UserInterface $user, string $providerKey): PostAuthenticationGuardToken
    {
        return parent::createAuthenticatedToken($user, $providerKey);
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     *
     * @throws Exception
     */
    public function getCredentials(Request $request)
    {
        $loginForm = $this->services->getFormService()->handlePostFormForAxiosCall($this->form->getLoginForm(), $request);
        if( $loginForm->isSubmitted() && $loginForm->isValid() ) {
            /**@var LoginFormDataDTO $loginFormData */
            $loginFormData = $loginForm->getData();
            return $loginFormData;
        }

        return new LoginFormDataDTO();
    }

    /**
     * @param LoginFormDataDTO $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|null
     */
    public function getUser(mixed $credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if( empty($credentials->getUsername()) ){
            $this->services->getLoggerService()->getLogger()->warning("Username is empty");
            return null;
        }

        $user = $this->userController->getOneByUsername($credentials->getUsername());
        if( empty($user) ){
            $this->services->getLoggerService()->getLogger()->warning("No user was found for give username. ", [
                "username" => $credentials->getUsername(),
            ]);

            return null;
        }

        return $user;
    }

    /**
     * @param LoginFormDataDTO $credentials
     * @param UserInterface $user
     * @return bool
     * @throws Exception
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if(!$this->services->getCsrfTokenValidatorService()->validateCsrfTokenInPostRequest($this->request)){
            $this->services->getLoggerService()->getLogger()->warning("Provided CSRF token is invalid");
            return false;
        }

        $validationResult = $this->services->getValidationService()->validateAndReturnArrayOfInvalidFieldsWithMessages($credentials);
        if( !$validationResult->isSuccess() ){
            $this->services->getLoggerService()->getLogger()->warning("Could not log-in, there are login form violations", [
                "violations" => $validationResult->getViolationsWithMessages(),
            ]);

            return false;
        }

        $isPasswordValid = $this->services->getUserSecurityService()->validatePasswordForUser($credentials->getPassword(), $user);
        if(!$isPasswordValid){
            $this->services->getLoggerService()->getLogger()->warning("Provided password is invalid");
            return false;
        }

        if( empty($credentials->getKey()) ){
            $this->services->getLoggerService()->getLogger()->warning("Key is missing");
            return false;
        }

        if( !$this->services->getEncryptionService()->isEncryptionKeyValid($credentials->getKey()) ){
            $this->services->getLoggerService()->getLogger()->warning("Invalid encryption key", [
                "encryptionKey" => $credentials->getKey(),
            ]);
            return false;
        }

        SessionService::setEncryptionKeyInSession($credentials->getKey());

        // Return `true` to cause authentication success
        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return JsonResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): JsonResponse
    {
        $message = $this->services->getTranslator()->trans('security.login.messages.OK');
        return BaseApiDTO::buildRedirectResponse('modules_dashboard_overview', $message)->toJsonResponse();
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        $message = $this->services->getTranslator()->trans('security.login.messages.UNAUTHORIZED');
        return BaseApiDTO::buildUnauthorizedResponse($message)->toJsonResponse();
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if( !$request->isXmlHttpRequest() ){
            return new RedirectResponse($this->urlGenerator->generate('login'));
        }

        $message  = $this->services->getTranslator()->trans('security.login.messages.UNAUTHORIZED');
        $response = BaseApiDTO::buildUnauthorizedResponse($message);
        $response->setRedirectRoute("login");
        return $response->toJsonResponse();
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}