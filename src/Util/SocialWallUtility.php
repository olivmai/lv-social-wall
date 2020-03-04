<?php

namespace App\Util;

use App\Entity\Event;
use App\Entity\Goody;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SocialWallUtility
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Event $event
     * @return Goody
     */
    public function pickAGoody(Event $event): Goody
    {
        $goodies = $event->getAvailableGoodies();
        if (!$goodies || empty($goodies)) {
            throw new NotFoundHttpException('No goodies found for this event');
        }
        $rand = rand(0, count($goodies)-1);

        $goody = $this->decrementGoodyQuantity($goodies->get($rand));

        return $goody;
    }

    /**
     * @param Goody $goody
     * @return Goody
     */
    private function decrementGoodyQuantity(Goody $goody): Goody
    {
        $goody->setQuantity($goody->getQuantity()-1);
        $this->entityManager->persist($goody);
        $this->entityManager->flush();

        return $goody;
    }
}
