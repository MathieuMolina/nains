<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $topic_title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $topic_creator = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $topic_last_user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $topic_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $topic_reply_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $topic_views = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'Id_topic')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: Message::class)]
    private Collection $Id_message;

    public function __construct()
    {
        $this->Id_message = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopicTitle(): ?string
    {
        return $this->topic_title;
    }

    public function setTopicTitle(string $topic_title): self
    {
        $this->topic_title = $topic_title;

        return $this;
    }

    public function getTopicCreator(): ?string
    {
        return $this->topic_creator;
    }

    public function setTopicCreator(string $topic_creator): self
    {
        $this->topic_creator = $topic_creator;

        return $this;
    }

    public function getTopicLastUser(): ?string
    {
        return $this->topic_last_user;
    }

    public function setTopicLastUser(string $topic_last_user): self
    {
        $this->topic_last_user = $topic_last_user;

        return $this;
    }

    public function getTopicDate(): ?\DateTimeInterface
    {
        return $this->topic_date;
    }

    public function setTopicDate(\DateTimeInterface $topic_date): self
    {
        $this->topic_date = $topic_date;

        return $this;
    }

    //Prochain paragraphe pour permettre de générer automatiquement la date lors du post du topic
//    public function __construct()
//    {
//        $this->date = new \DateTime('now');
//    }

    public function getTopicReplyDate(): ?\DateTimeInterface
    {
        return $this->topic_reply_date;
    }

    public function setTopicReplyDate(\DateTimeInterface $topic_reply_date): self
    {
        $this->topic_reply_date = $topic_reply_date;

        return $this;
    }

    public function getTopicViews(): ?int
    {
        return $this->topic_views;
    }

    public function setTopicViews(int $topic_views): self
    {
        $this->topic_views = $topic_views;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getIdMessage(): Collection
    {
        return $this->Id_message;
    }

    public function addIdMessage(Message $idMessage): self
    {
        if (!$this->Id_message->contains($idMessage)) {
            $this->Id_message->add($idMessage);
            $idMessage->setTopic($this);
        }

        return $this;
    }

    public function removeIdMessage(Message $idMessage): self
    {
        if ($this->Id_message->removeElement($idMessage)) {
            // set the owning side to null (unless already changed)
            if ($idMessage->getTopic() === $this) {
                $idMessage->setTopic(null);
            }
        }

        return $this;
    }
}
