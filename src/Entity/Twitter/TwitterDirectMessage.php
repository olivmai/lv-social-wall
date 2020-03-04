<?php

namespace App\Entity\Twitter;

use App\Entity\EntityTimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TwitterDirectMessageRepository")
 */
class TwitterDirectMessage
{
    use EntityTimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recipientId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $messageData;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipientId(): ?string
    {
        return $this->recipientId;
    }

    public function setRecipientId(string $recipientId): self
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    public function getMessageData(): ?string
    {
        return $this->messageData;
    }

    public function setMessageData(string $messageData): self
    {
        $this->messageData = $messageData;

        return $this;
    }
}
