<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $topic_id = null;

    #[ORM\Column(length: 255)]
    private ?string $message_creator = null;

    #[ORM\Column(length: 500)]
    private ?string $message_content = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $message_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopicId(): ?int
    {
        return $this->topic_id;
    }

    public function setTopicId(int $topic_id): self
    {
        $this->topic_id = $topic_id;

        return $this;
    }

    public function getMessageCreator(): ?string
    {
        return $this->message_creator;
    }

    public function setMessageCreator(string $message_creator): self
    {
        $this->message_creator = $message_creator;

        return $this;
    }

    public function getMessageContent(): ?string
    {
        return $this->message_content;
    }

    public function setMessageContent(string $message_content): self
    {
        $this->message_content = $message_content;

        return $this;
    }

    public function getMessageDate(): ?\DateTimeInterface
    {
        return $this->message_date;
    }

    public function setMessageDate(\DateTimeInterface $message_date): self
    {
        $this->message_date = $message_date;

        return $this;
    }
}
