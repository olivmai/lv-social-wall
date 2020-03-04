<?php

namespace App\Entity\Twitter;

use App\Entity\Event;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TweetRepository")
 */
class Tweet
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="user_id")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255, name="user_avatar")
     */
    private $userAvatar;

    /**
     * @ORM\Column(type="string", length=255, name="user_screen_name")
     */
    private $userScreenName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $following;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $moderated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="tweets")
     */
    private $event;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * Tweet constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->moderated = false;
    }

    public function __toString()
    {
        return 'Tweet by '.$this->getUserId().' : '.$this->getId();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function isFollowing(): ?bool
    {
        return $this->following;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @param bool $moderated
     * @return Tweet
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;
        return $this;
    }

    public function isModerated(): bool
    {
        return $this->moderated;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $following
     */
    public function setFollowing($following): void
    {
        $this->following = $following;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * @param mixed $userAvatar
     */
    public function setUserAvatar($userAvatar): void
    {
        $this->userAvatar = $userAvatar;
    }

    /**
     * @return mixed
     */
    public function getUserScreenName()
    {
        return $this->userScreenName;
    }

    /**
     * @param mixed $userScreenName
     */
    public function setUserScreenName($userScreenName): void
    {
        $this->userScreenName = $userScreenName;
    }
}
