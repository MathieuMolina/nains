<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your title must be at least {{ limit }} characters long',
        maxMessage: 'Your title cannot be longer than {{ limit }} characters',
    )]
    private string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\Length(
        min: 2,
        max: 1000,
        minMessage: 'Your content must be at least {{ limit }} characters long',
        maxMessage: 'Your content cannot be longer than {{ limit }} characters',
    )]
    private string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreated;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Topic $topic = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'messages')]
    private ?self $parentMessage = null;

    #[ORM\OneToMany(mappedBy: 'parentMessage', targetEntity: self::class)]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->dateCreated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getMessage(): ?self
    {
        return $this->messages;
    }

    public function setMessage(?self $message): self
    {
        $this->messages = $message;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

public function addMessage(self $message): self
{
    if (!$this->messages->contains($message)) {
        $this->messages->add($message);
        $message->setMessage($this);
    }

    return $this;
}

public function removeMessage(self $message): self
{
    if ($this->messages->removeElement($message)) {
        // set the owning side to null (unless already changed)
        if ($message->getMessage() === $this) {
            $message->setMessage(null);
        }
    }

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
}