<?php


namespace App\Service\Attribute;


use App\Controller\Core\Application;
use App\Service\Routing\UrlMatcherService;
use Laminas\Code\Reflection\MethodReflection;
use ReflectionAttribute;
use ReflectionException;

/**
 * This class handles reading/checking php8 attributes on classes and methods
 *
 * Class AttributeReaderService
 * @package App\Service\Attribute
 */
class AttributeReaderService
{

    private UrlMatcherService $urlMatcherService;
    private Application $application;

    public function __construct(UrlMatcherService $urlMatcherService, Application $application)
    {
        $this->application       = $application;
        $this->urlMatcherService = $urlMatcherService;
    }

    /**
     * Will check if given route has attribute
     *
     * @param string $calledUri
     * @param string $attributeClass
     * @return bool
     * @throws ReflectionException
     */
    public function hasUriAttribute(string $calledUri, string $attributeClass): bool
    {
        $classWithMethodForUri = $this->urlMatcherService->getClassAndMethodForCalledUrl($calledUri);
        if( empty($classWithMethodForUri) ){
            $this->application->getLogger()->warning("Url matcher returned null for uri, so no attribute can be looked for");
            return false;
        }

        $attributes = $this->getAttributesForRoute($classWithMethodForUri);
        foreach($attributes as $reflectionAttribute){
            if( $attributeClass === $reflectionAttribute->getName() ){
                return true;
            }
        }

        return false;
    }

    /**
     * Will return array of attributes for given class::method string
     *
     * @param string $classWithMethodForUri
     * @return ReflectionAttribute[]
     * @throws ReflectionException
     */
    private function getAttributesForRoute(string $classWithMethodForUri): array
    {
        $methodReflection  = new MethodReflection($classWithMethodForUri);
        $arrayOfAttributes = $methodReflection->getAttributes();

        return $arrayOfAttributes;
    }

}