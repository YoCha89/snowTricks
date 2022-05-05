<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class VidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('mediaPath',TextType::class , [
                'label' => 'Ajouter une vidéo',
                'mapped' => false, 
                'data' => 'Cc le lien de la vidéo'
            ])
            ->add('trick', EntityType::class, [
            // looks for choices from this entity
            'class' => Trick::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            'multiple' => false,
            'required' => true,
            // 'expanded' => true,
            ])
            ->add('type', HiddenType::class, [
            'data' => 'vid',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
