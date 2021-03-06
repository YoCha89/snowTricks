<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\media;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name')
            ->add('content', TextareaType::class)
            ->add('mediaTitle', null,[
                'label' => 'Titre de l\'image',
                'mapped' => false,
            ])
            ->add('medias', FileType::class,[
                'label' => 'Ajouter une image',
                'mapped' => false, 
                'attr' =>array('id' => 'fileStyle', 'style' => 'display:none'),
                'label_attr' => array('style' => 'background-color:#7b8a8b; padding:10px; border-radius: 6px 6px 6px 6px; color:#ecf0f1'),
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
