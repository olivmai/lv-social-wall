<?php

namespace App\TwitterApi;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\Event;
use App\Entity\Twitter\TwitterDirectMessage;
use Doctrine\ORM\EntityManager;

class TwitterApiUtility
{
    private $twitterApiConsumerKey;
    private $twitterApiConsumerKeySecret;
    private $twitterApiTokenAccess;
    private $twitterApiTokenAccessSecret;
    private $entityManager;
    private $twitterConnection;

    /**
     * TwitterApiUtility constructor.
     * @param string $twitterApiConsumerKey
     * @param string $twitterApiConsumerKeySecret
     * @param string $twitterApiTokenAccess
     * @param string $twitterApiTokenAccessSecret
     * @param EntityManager $entityManager
     */
    public function __construct(
        string $twitterApiConsumerKey,
        string $twitterApiConsumerKeySecret,
        string $twitterApiTokenAccess,
        string $twitterApiTokenAccessSecret,
        EntityManager $entityManager
    ) {
        $this->twitterApiConsumerKey = $twitterApiConsumerKey;
        $this->twitterApiConsumerKeySecret = $twitterApiConsumerKeySecret;
        $this->twitterApiTokenAccess = $twitterApiTokenAccess;
        $this->twitterApiTokenAccessSecret = $twitterApiTokenAccessSecret;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Event $event
     * @param null $id
     * @return mixed
     */
    public function searchTweet(Event $event, $id = null)
    {
        $twitterConnection = $this->initTwitterConnection();
        $parameters = [
            'q' => $event->getHashtags(),
            'result_type' => 'recent',
            'count' => 1,
        ];

        if ($id) {
            $parameters['since_id'] = $id;
        }

        $statuses = $twitterConnection->get("search/tweets", $parameters);
        if (200 !== $twitterConnection->getLastHttpCode()) {
            throw new \RuntimeException(sprintf(
                'Search tweets failed: "%s" (code %d)',
                $twitterConnection->getLastBody()->errors[0]->message,
                $twitterConnection->getLastBody()->errors[0]->code
            ));
        }

        return $statuses;
    }

    /**
     * @return TwitterOAuth
     */
    private function initTwitterConnection()
    {
        if (null === $this->twitterConnection) {
            $this->twitterConnection = new TwitterOAuth($this->twitterApiConsumerKey, $this->twitterApiConsumerKeySecret, $this->twitterApiTokenAccess, $this->twitterApiTokenAccessSecret);
        }

        return $this->twitterConnection;
    }

    /**
     * @param Event $event
     * @param null $lastTweetId
     * @return mixed
     */
    public function getLastTweet(Event $event, $lastTweetId = null)
    {
        $lastTweet = $this->searchTweet($event, $lastTweetId);

        return $lastTweet;
    }

    /**
     * @param TwitterDirectMessage $directMessage
     * @return array|object
     */
    public function sendDirectMessage(TwitterDirectMessage $directMessage)
    {
        $twitterConnection = $this->initTwitterConnection();
        $data = $this->getFormattedData($directMessage);
        $status = $twitterConnection->post('direct_messages/events/new', $data, true);
        /*if (200 !== $twitterConnection->getLastHttpCode()) {
            throw new \RuntimeException(sprintf('Create a new direct message to %d failed: "%s"',
                $directMessage->getRecipientId(),
                $twitterConnection->getLastBody()
            ));
        }*/

        return $status;
    }

    /**
     * @param TwitterDirectMessage $directMessage
     * @return array
     */
    private function getFormattedData(TwitterDirectMessage $directMessage)
    {
        return [
            'event' => [
                'type' => 'message_create',
                'message_create' => [
                    'target' => [
                        'recipient_id' => (int)$directMessage->getRecipientId(),
                    ],
                    'message_data' => [
                        'text' => $directMessage->getMessageData(),
                    ]
                ]
            ]
        ];
    }
}
