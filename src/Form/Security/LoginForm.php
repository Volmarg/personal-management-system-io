<?php

namespace App\Form\Security;

use App\DTO\Internal\Form\Security\LoginFormDataDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LoginForm
 * @package App\Form
 */
class LoginForm extends AbstractType
{
    const FIELD_NAME_USERNAME = "username";
    const FIELD_NAME_PASSWORD = "password";

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NAME_USERNAME, TextType::class)
            ->add(self::FIELD_NAME_PASSWORD, PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => LoginFormDataDTO::class,
            "csrf_protection" => false,
            "allow_extra_fields" => true,
        ]);
    }

}
