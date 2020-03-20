<?php

namespace App\Controller;

use Olivmai\LinkvalueOAuth2Bundle\Provider\LinkvalueProvider;
use Olivmai\LinkvalueOAuth2Bundle\Security\Authenticator\LinkvalueAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class LinkvalueController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/linkvalue", name="connect_linkvalue_start")
     * @param LinkvalueProvider $linkvalueProvider
     * @return RedirectResponse
     */
    public function connectAction(LinkvalueProvider $linkvalueProvider): RedirectResponse
    {
        // redirect to LV Connect and then back to connect_linkvalue_check, see below
        return $linkvalueProvider->redirect();
    }

    /**
     * @Route("/connect/linkvalue/check", name="connect_linkvalue_check")
     * @param Request $request
     * @param LinkvalueProvider $linkvalueProvider
     */
    public function connectCheckAction()
    {
        // leave empty to use Guard authentication
    }
}
