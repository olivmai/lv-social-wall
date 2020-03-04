<?php

namespace App\Entity\Factory;

use App\Entity\Event;
use App\Entity\Twitter\Tweet;

class TweetFactory
{
    /**
     * @param Event $event
     * @param int $id
     * @param string $userId
     * @param string $userScreenName
     * @param string $userAvatar
     * @param int $following
     * @param string|null $content
     * @return Tweet
     * @throws \Exception
     */
    public static function create(Event $event, int $id, string $userId, string $userScreenName, string $userAvatar, int $following, string $content = null)
    {
        $tweet = new Tweet();
        $tweet->setEvent($event);
        $tweet->setId($id);
        $tweet->setUserId($userId);
        $tweet->setUserScreenName($userScreenName);
        $tweet->setUserAvatar($userAvatar);
        $tweet->setFollowing($following);
        $tweet->setContent($content);

        return $tweet;
    }

    /**
     * @param Event $event
     * @return Tweet
     * @throws \Exception
     */
    public static function fake(Event $event): Tweet
    {
        return self::create(
            $event,
            rand(76345, 4676545676),
            'DTUYJFKUGIL67',
            'userTest',
            'https://picsum.photos/300/300/?blur',
            true,
            'Just to know if it works #devmode. lorem ipsum tralala...'
        );
    }
}