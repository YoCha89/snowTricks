<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ThumbType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ThumbnailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('thumbs', CollectionType::class,  array(
                'entry_type' => ThumbType::class,
                'label' => 'id',
                ));
        ;
    }
}

/*
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
CheckboxType::class*/