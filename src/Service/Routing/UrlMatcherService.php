<?php


namespace App\Service\Routing;


use App\Controller\Core\Services;
use Exception;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

/**
 * This class handles logic for finding matching controllers/methods for given url
 *
 * Class UrlMatcherService
 * @package App\Service\Routing
 */
class UrlMatcherService
{

    const URL_MATCHER_RESULT_CONTROLLER_WITH_METHOD = "_controller";

    /**
     * @var Services $services
     */
    private Services $services;

    /**
     * @var UrlMatcherInterface $urlMatcher
     */
    private UrlMatcherInterface $urlMatcher;

    public function __construct(UrlMatcherInterface $urlMatcher, Services $services)
    {
        $this->services   = $services;
        $this->urlMatcher = $urlMatcher;
    }

    /**
     * Will return `class:method` for given url or null if nothing was found
     *
     * @param string $url
     * @return string|null
     */
    public function getClassAndMethodForCalledUrl(string $url): ?string
    {
        try{
            $dataArray = $this->urlMatcher->match($url);
        }catch(Exception $e){
            $this->services->getLoggerService()->logException($e, [
                "No class with method was found for url", [
                    "url" => $url,
                ]
            ]);
            return null;
        }

        return $dataArray[self::URL_MATCHER_RESULT_CONTROLLER_WITH_METHOD];
    }

}