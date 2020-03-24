<?php

namespace App\Entity;

use App\Entity\Twitter\Tweet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Vich\Uploadable
 */
class Event
{
    use EntityTimestampableTrait;
    use EntityUploadableImageTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Twitter\Tweet", mappedBy="event")
     */
    private $tweets;

    /**
     * Many Event have Many Goodies.
     * @ORM\ManyToMany(targetEntity="App\Entity\Goody")
     * @ORM\JoinTable(name="events_goodies",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=true)},
     *      inverseJoinColumns={@ORM\JoinColumn(name="goody_id", referencedColumnName="id", unique=true, nullable=true)}
     *      )
     */
    private $goodies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $callToAction;

    /**
     * Events have one bigPrize
     * @ORM\ManyToOne(targetEntity="App\Entity\BigPrize")
     * @ORM\JoinColumn(name="big_prize_id", referencedColumnName="id")
     */
    private $bigPrize;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $hashtags;

    /**
     * @ORM\Column(type="text")
     */
    private $directMessageData;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $drawTime;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->tweets = new ArrayCollection();
        $this->goodies = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|Tweet[]
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(Tweet $tweet): self
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets[] = $tweet;
            $tweet->setEvent($this);
        }

        return $this;
    }

    public function removeTweet(Tweet $tweet): self
    {
        if ($this->tweets->contains($tweet)) {
            $this->tweets->removeElement($tweet);
            // set the owning side to null (unless already changed)
            if ($tweet->getEvent() === $this) {
                $tweet->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Goody[]
     */
    public function getGoodies(): Collection
    {
        return $this->goodies;
    }

    public function addGoody(Goody $goody): self
    {
        if (!$this->goodies->contains($goody)) {
            $this->goodies[] = $goody;
        }

        return $this;
    }

    public function removeGoody(Goody $goody): self
    {
        if ($this->goodies->contains($goody)) {
            $this->goodies->removeElement($goody);
        }

        return $this;
    }

    public function getCallToAction(): ?string
    {
        return $this->callToAction;
    }

    public function setCallToAction(?string $callToAction): self
    {
        $this->callToAction = $callToAction;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBigPrize()
    {
        return $this->bigPrize;
    }

    /**
     * @param mixed $bigPrize
     */
    public function setBigPrize($bigPrize): void
    {
        $this->bigPrize = $bigPrize;
    }

    public function getEventDate()
    {
        return ($this->getEndDate())
            ? $this->getStartDate()->format('Y/m/d H:i:s').' - '.$this->getEndDate()->format('Y/m/d H:i:s')
            : $this->getStartDate()->format('Y/m/d H:i:s');
    }

    /**
     * @return mixed
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * @param mixed $hashtags
     */
    public function setHashtags($hashtags): void
    {
        $this->hashtags = $hashtags;
    }

    /**
     * @return mixed
     */
    public function getDirectMessageData()
    {
        return $this->directMessageData;
    }

    /**
     * @param mixed $directMessageData
     */
    public function setDirectMessageData($directMessageData): void
    {
        $this->directMessageData = $directMessageData;
    }

    /**
     * @return Collection
     */
    public function getAvailableGoodies(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gt('quantity', 0));

        return $this->goodies->matching($criteria);
    }

    /**
     * @return \DateTime
     */
    public function getDrawTime(): \DateTime
    {
        return $this->drawTime;
    }

    /**
     * @param \DateTime $drawTime
     */
    public function setDrawTime(\DateTime $drawTime): void
    {
        $this->drawTime = $drawTime;
    }
}
