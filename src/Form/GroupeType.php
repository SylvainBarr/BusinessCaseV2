<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Groupe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'form.groupe.name'
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'label' => 'form.groupe.genre',
                'choice_label' => 'name',
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
            'data_class' => Groupe::class,
        ]);
    }
}
