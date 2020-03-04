<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Twitter\Tweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tweet[]    findAll()
 * @method Tweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tweet::class);
    }

    public function findLastTweet(Event $event)
    {
        $queryBuilder = $this->createQueryBuilder('tweet');
        $queryBuilder   ->join('tweet.event', 'event')
                        ->where('event = :event')
                        ->setParameter('event', $event)
                        ->orderBy('tweet.createdAt', 'DESC');
        $queryBuilder->setMaxResults(1);
        $result = $queryBuilder->getQuery()->getResult();
        return ($result) ? $result[0] : null;
    }
}
