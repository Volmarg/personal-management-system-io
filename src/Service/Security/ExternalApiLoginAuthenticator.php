<?php

namespace App\Service\Security;

use App\Attribute\Action\ExternalActionAttribute;
use App\Controller\ApiUserController;
use App\Controller\Core\Env;
use App\Controller\Core\Services;
use App\DTO\BaseApiDTO;
use Exception;
use Firebase\JWT\JWT;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use TypeError;

/**
 * Handles the authentication for external API calls
 * @link https://symfony.com/doc/current/security/guard_authentication.html#the-guard-authenticator-methods
 *
 * @package App\Service\Security
 */
class ExternalApiLoginAuthenticator extends AbstractGuardAuthenticator
{
    const KEY_ACCESS_DATA = "accessData"; // todo: add this to bridge also, + handle creating authentication data there wrapped in JWT
    const ALG_HS256       = "HS256";

    const KEY_PROPERTY_LOGIN    = "login";
    const KEY_PROPERTY_PASSWORD = "password";

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var ApiUserController $apiUserController
     */
    private ApiUserController $apiUserController;

    /**
     * @var string $login
     */
    private string $login;

    /**
     * @var string $password
     */
    private string $password;

    public function __construct(
        Services          $services,
        ApiUserController $apiUserController,
    )
    {
        $this->apiUserController = $apiUserController;
        $this->services          = $services;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     *
     * @throws ReflectionException
     */
    public function supports(Request $request): bool
    {
        return $this->services->getAttributeReader()->hasUriAttribute($request->getRequestUri(), ExternalActionAttribute::class);
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
     * be passed to getUser() as $credentials - cannot be null as Symfony throws exception then
     *
     * @throws Exception
     */
    public function getCredentials(Request $request)
    {
        if( !$request->headers->has(self::KEY_ACCESS_DATA) ){
            return false;
        }

        $jwt = $request->headers->get(self::KEY_ACCESS_DATA);
        return $jwt;
    }

    /**
     * @param string $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|null
     */
    public function getUser(mixed $credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if( empty($credentials) ){
            $this->services->getLoggerService()->getLogger()->warning("The credentials are empty");
            return null;
        }

        try{
            $decodedJwtObject  = JWT::decode($credentials, Env::getJwtSecret(), [self::ALG_HS256]);
        }catch(Exception | TypeError $e){
            $this->services->getLoggerService()->getLogger()->warning("Invalid Jwt", [
                "reason" => $e->getMessage(),
            ]);
            return null;
        }

        if( !property_exists($decodedJwtObject, self::KEY_PROPERTY_LOGIN ) ){
            $this->services->getLoggerService()->getLogger()->warning("Missing credentials in jwt", [
                "info" => "JWT decoded object is missing property: " . self::KEY_PROPERTY_LOGIN,
            ]);
            return null;
        }

        if( !property_exists($decodedJwtObject, self::KEY_PROPERTY_PASSWORD ) ){
            $this->services->getLoggerService()->getLogger()->warning("Missing credentials in jwt", [
                "info" => "JWT decoded object is missing property: " . self::KEY_PROPERTY_PASSWORD,
            ]);
            return null;
        }

        $login    = $decodedJwtObject->{self::KEY_PROPERTY_LOGIN};
        $password = $decodedJwtObject->{self::KEY_PROPERTY_PASSWORD};

        if( empty($login) ){
            $this->services->getLoggerService()->getLogger()->warning("Username is empty");
            return null;
        }

        if( empty($password) ){
            $this->services->getLoggerService()->getLogger()->warning("Password is empty");
            return null;
        }

        $user = $this->apiUserController->getOneByUsername($login);
        if( empty($user) ){
            $this->services->getLoggerService()->getLogger()->warning("No user was found for give username. ", [
                "username" => $login,
            ]);

            return null;
        }

        $this->login    = $login;
        $this->password = $password;

        return $user;
    }

    /**
     * @param string $credentials
     * @param UserInterface $user
     * @return bool
     * @throws Exception
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $isPasswordValid = $this->services->getUserSecurityService()->validatePasswordForUser($this->password, $user);
        if(!$isPasswordValid){
            $this->services->getLoggerService()->getLogger()->warning("Provided password is invalid");
            return false;
        }

        // Return `true` to cause authentication success
        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
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
        $message  = $this->services->getTranslator()->trans('security.login.messages.UNAUTHORIZED');
        $response = BaseApiDTO::buildUnauthorizedResponse($message);
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