<?php


namespace App\Action\Vue;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Consist of vue routes - this is needed to obtain the base twig template structure for further processing in Vue
 * as Vue mounts the instance on top the element defined in Twig.
 *
 * Class VueAction
 * @package App\Action\Vue
 */
class VueAction extends AbstractController
{
    const TEMPLATE_BASE             = "base.html.twig";
    const VUE_ID_VUE_APP            = "vueApp";
    const VUE_ID_VUE_APP_BLANK_BASE = "vueAppBlankBase";

    /**
     * This action is needed to allow vue calling pages via standard url calls,
     * This consist later on of sidebar, footer etc.
     * the only returned thing here is a response - to prevent symfony from crashing when vue switches pages
     */
    #[Route("/module/notes/category/{id}",  name: "modules_notes_category",      methods: [Request::METHOD_GET])]
    #[Route("/module/dashboard/overview",   name: "modules_dashboard_overview",  methods: [Request::METHOD_GET])]
    #[Route("/module/passwords/group/{id}", name: "modules_passwords_group",     methods: [Request::METHOD_GET])]
    public function handleVueCallForPageDisplay(): Response
    {
        return $this->render(self::TEMPLATE_BASE, [
            "vueId" => self::VUE_ID_VUE_APP,
        ]);
    }

    /**
     * This action is needed to allow vue calling pages via standard url calls,
     * This regards all pages like which dont use standard GUI: login / register / 404 / 500 etc.
     * the only returned thing here is a response - to prevent symfony from crashing when vue switches pages
     */
    #[Route("/login", name: "login", methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function handleVueCallForBlankBasePage(): Response
    {
        return $this->render(self::TEMPLATE_BASE, [
            "vueId" => self::VUE_ID_VUE_APP_BLANK_BASE,
        ]);
    }

}