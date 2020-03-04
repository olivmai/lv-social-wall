<?php

namespace App\Entity\Factory;

use App\Entity\Twitter\TwitterDirectMessage;

/**
 * Class TwitterDirectMessageFactory
 * @package App\TwitterApi\Factory
 */
class TwitterDirectMessageFactory
{
    /**
     * @param string $recipientId
     * @param string $messageData
     * @return TwitterDirectMessage
     */
    public static function create(string $recipientId, string $messageData): TwitterDirectMessage
    {
        $directMessage = new TwitterDirectMessage();
        $directMessage->setRecipientId($recipientId);
        $directMessage->setMessageData($messageData);

        return $directMessage;
    }
}