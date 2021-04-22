<?php


namespace App\Controller\Core;


use App\Form\Security\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class Form extends AbstractController
{

    /**
     * @param ?object   $data
     * @param array     $options
     * @return FormInterface
     */
    public function getLoginForm(object $data = null, array $options = []): FormInterface
    {
        return $this->createForm(LoginForm::class, $data, $options);
    }

}