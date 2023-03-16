<?php

namespace App\Twig;

use App\Repository\TopicRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TopicExtension extends AbstractExtension
{
    /**
     * @var TopicRepository
     */
    private TopicRepository $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_all_topic', [$this, 'getAllTopic']),
        ];
    }

    public function getAllTopic(): array
    {
        return $this->topicRepository->findAllWhereParentNullOrderByNumberMessage();
    }
}