<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SocialWallController extends AbstractController
{
    /**
     * @Route("/event/{id}/play", name="play_event")
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function playAction(Event $event, TweetRepository $repository)
    {
        $tweets = $repository->findBy(['event' => $event], ['createdAt' => 'DESC'], 5);
        return $this->render('event/play.html.twig', [
            'event' => $event,
            'lastFiveTweets' => $tweets
        ]);
    }
}
