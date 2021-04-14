<?php


namespace App\Service\Validation;


use App\DTO\Internal\ValidationResultDTO;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    /**
     * @var ValidatorInterface $validator
     */
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates the object and returns the array of violations
     *
     * @param object $object
     * @return ValidationResultDTO
     */
    public function validateAndReturnArrayOfInvalidFieldsWithMessages(object $object): ValidationResultDTO
    {
        $validationResultDto    = new ValidationResultDTO();
        $violations             = $this->validator->validate($object);
        $violationsWithMessages = [];

        /**@var $constraintViolation ConstraintViolation*/
        foreach($violations as $constraintViolation){
            $violationsWithMessages[$constraintViolation->getPropertyPath()] = $constraintViolation->getMessage();
        }

        $validationResultDto->setSuccess(true);
        if( !empty($violationsWithMessages) ){
            $validationResultDto->setSuccess(false);
            $validationResultDto->setViolationsWithMessages($violationsWithMessages);
        }

        return $validationResultDto;
    }

}