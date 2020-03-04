<?php

namespace App\Manager;

use App\Entity\Event;
use App\Entity\Factory\TweetFactory;
use App\Entity\Twitter\Tweet;
use App\Entity\Twitter\TwitterDirectMessage;
use App\TwitterApi\TwitterApiUtility;
use Doctrine\ORM\EntityManagerInterface;

class TweetManager
{
    private $entityManager;

    /**
     * @var TwitterApiUtility
     */
    private $twitterApiUtility;
    /**
     * @var string
     */
    private $env;

    /**
     * TweetManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param TwitterApiUtility $twitterApiUtility
     */
    public function __construct(EntityManagerInterface $entityManager, TwitterApiUtility $twitterApiUtility, string $env)
    {
        $this->entityManager = $entityManager;
        $this->twitterApiUtility = $twitterApiUtility;
        $this->env = $env;
    }

    /**
     * @param Event $event
     * @param \stdClass $tweetInfo
     * @return Tweet
     * @throws \Exception
     */
    public function registerTweetInfo(Event $event, \stdClass $tweetInfo): Tweet
    {
        if (!$tweetInfo) {
            throw new \InvalidArgumentException('No tweet info available for registration');
        }

        $tweet = TweetFactory::create(
            $event,
            $tweetInfo->id,
            $tweetInfo->user->id,
            $tweetInfo->user->screen_name,
            $tweetInfo->user->profile_image_url_https,
            $tweetInfo->user->following,
            $tweetInfo->text
        );

        $this->entityManager->persist($tweet);
        $this->entityManager->flush();

        return $tweet;
    }

    /**
     * @param string $tweetId
     * @return Tweet
     * @throws \Exception
     */
    public function moderateTweet(string $tweetId): Tweet
    {
        try {
            $tweet = $this->entityManager->getRepository('App:Tweet')->find($tweetId);
            $tweet->setModerated(true);

            $this->entityManager->persist($tweet);
            $this->entityManager->flush();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $tweet;
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getModeratedTweets(Event $event): array
    {
        $moderatedTweets = $this->entityManager->getRepository('App:Tweet')->findBy(['event' => $event, 'moderated' => true]);
        $moderatedTweetsIds = [];
        foreach ($moderatedTweets as $moderatedTweet) {
            $moderatedTweetsIds[] = $moderatedTweet->getId();
        }
        return $moderatedTweetsIds;
    }

    /**
     * @param Event $event
     * @return mixed
     * @throws \Exception
     */
    public function getLastTweet(Event $event)
    {
        if ("dev" === $this->env) {
            $tweet = TweetFactory::fake($event);
            $this->entityManager->persist($tweet);
            $this->entityManager->flush();
            return $tweet;
        }

        // get last tweet since tweet id from Twitter API
        /** @var Tweet $lastTweetInDb */
        $lastTweetInDb = $this->entityManager->getRepository('App\Entity\Twitter\Tweet')->findLastTweet($event);
        $lastTweet = $this->twitterApiUtility->getLastTweet($event, ($lastTweetInDb) ? $lastTweetInDb->getId() : null);

        // if no result
        if (!$lastTweet->statuses) {
            return null;
        }

        // if tweet already exists in db
        if ($this->tweetExists($event, $lastTweet->statuses[0]->id_str)) {
            return null;
        }

        // if new tweet, register and return it
        $tweet = $this->registerTweetInfo($event, $lastTweet->statuses[0]);

        // if tweet author is not following, register it in db but don't return it
        if (false === $lastTweet->statuses[0]->user->following) {
            return null;
        }

        // send private message to player
        //$this->sendPrivateMessage($twitterDirecteMessage);
        return $tweet;
    }

    /**
     * @param Event $event
     * @param string $tweetId
     * @return Tweet|null|object
     */
    private function tweetExists(Event $event, string $tweetId)
    {
        $tweet = $this->entityManager->getRepository('App\Entity\Twitter\Tweet')->findOneBy(['event' => $event, 'id' => $tweetId]);
        return $tweet;
    }

    /**
     * @param TwitterDirectMessage $directMessage
     */
    public function sendDirectMessage(TwitterDirectMessage $directMessage)
    {
        $this->twitterApiUtility->sendDirectMessage($directMessage);
    }
}
