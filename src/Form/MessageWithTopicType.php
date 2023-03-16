<?php

namespace App\Form;

use App\Entity\Topic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageWithTopicType extends MessageType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('topic', EntityType::class, [
                'required' => false,
                'label' => 'Choisir un topic',
                'class' => Topic::class,
                'choice_label' => 'name'
            ])
        ;
    }
}