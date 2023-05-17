<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column]
    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private int $views = 0;

    /**
     * @ORM\PreUpdate
     */
    public function updateViews(): void
    {
        $this->views += 1;
    }


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $message = null;

    private Collection $topics;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->topics = new ArrayCollection();

        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setTopic($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getTopic() === $this) {
                $message->setTopic(null);
            }
        }

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getTopic(): ?Collection
    {
        return $this->topics;
    }

    public function setTopic(?Collection $topic): Topic
    {
        $this->topics = $topic;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(self $topic): self
    {
        if (!$this->$topic->contains($topic)) {
            $this->topics->add($topic);
            $topic->setTopic(null);
        }

        return $this;
    }

    public function removeTopic(self $topic): self
    {
        if ($this->$topic->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getTopic() === $this) {
                $topic->setTopic(null);
            }
        }

        return $this;
    }
}
