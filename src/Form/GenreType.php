<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Groupe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'form.genre.name'
            ])
            ->add('parentsGenres', EntityType::class, [
                'label'=> 'form.genre.genre',
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                    }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
