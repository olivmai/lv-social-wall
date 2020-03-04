<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\Twitter\Tweet;
use App\Manager\TweetManager;
use App\Mercure\Publisher;
use App\Util\SocialWallUtility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mercure\Update;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetLastTweetCommand extends Command
{
    protected static $defaultName = 'app:twitter:get-last';

    /**
     * @var TweetManager
     */
    private $tweetManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SocialWallUtility
     */
    private $socialWallUtility;

    /**
     * @var Publisher
     */
    private $publisher;

    public function __construct(
        TweetManager $tweetManager,
        EntityManagerInterface $entityManager,
        SocialWallUtility $socialWallUtility,
        Publisher $publisher
    ) {
        $this->tweetManager = $tweetManager;
        $this->entityManager = $entityManager;
        $this->socialWallUtility = $socialWallUtility;
        parent::__construct();
        $this->publisher = $publisher;
    }

    protected function configure()
    {

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Get last Tweet command');

        /** @var Event $event */
        $event = $this->entityManager->getRepository('App:Event')->find(1);
        /** @var Tweet $lastTweet */
        $lastTweet = $this->tweetManager->getLastTweet($event);

        // pick the goody the user won
        $goody = $this->socialWallUtility->pickAGoody($event);

        /** Tweet $lastTweet */
        if ($lastTweet) {
            $io->writeln('New tweet found');
            $data = [
                'lastTweetId' => $lastTweet->getId(),
                'tweetContent' => filter_var($lastTweet->getContent(), FILTER_SANITIZE_STRING),
                'user' => $lastTweet->getUserScreenName(),
                'img' => $lastTweet->getUserAvatar(),
                'goody' => $goody->getId(),
            ];

            try {
                $update = new Update('lv-social-wall', json_encode($data));
                $this->publisher->publish($update);
                $io->success('Data sent to Mercure Hub.');
            } catch (\Exception $exception) {
                $io->error($exception->getMessage());
            }
        }

        return 0;
    }
}
