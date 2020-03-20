<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Debug\TraceablePublisher;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        dump($this->getUser());
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->redirectToRoute('easyadmin');
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        return $this->render('login.html.twig');
    }

    /**
     * @Route("/mercure", name="mercure")
     * @param Publisher $publisher
     */
    public function pingMercure(MessageBusInterface $bus)
    {
        $update = new Update("lv-social-wall", json_encode(['test' => 'pour voir']));
        $bus->dispatch($update);
        return new Response("Event sent");
    }

}