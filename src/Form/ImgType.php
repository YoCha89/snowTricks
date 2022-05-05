<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ImgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('mediaPath', FileType::class,[
                'label' => 'Ajouter une image',
                'mapped' => false, 
                'attr' =>array('id' => 'fileStyle', 'style' => 'display:none'),
                'label_attr' => array('style' => 'background-color:#7b8a8b; padding:10px; border-radius: 6px 6px 6px 6px; color:#ecf0f1'),
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
            'data' => 'img',
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
