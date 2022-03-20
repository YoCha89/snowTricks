<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\media;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name')
            ->add('content')
            ->add('medias', EntityType::class, [
            // looks for choices from this entity
            'class' => Media::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'title',

            // used to render a select box, check boxes or radios
            'multiple' => true,
            // 'expanded' => true,
            ])
            ->add('category', EntityType::class, [
            // looks for choices from this entity
            'class' => Category::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
