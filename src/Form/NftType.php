<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\Nft;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'form.nft.name'
            ])
            ->add('image', FileType::class,[
                'label' => 'form.nft.image',
                'required'=> true,
                'mapped' =>false,
                'constraints' => [
                    new File(
                        mimeTypes: ['image/png', 'image/jpeg'],
                        mimeTypesMessage: 'Déposer seulement un .jpg ou .png'
                    )
                ]
            ])
            ->add('dateDrop', DateType::class, [
                'label'=> 'form.nft.dateDrop',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('anneeAlbum', null, [
                'label' => 'form.nft.anneeAlbum'
            ])
            ->add('identificationToken', null, [
                'label' => 'form.nft.token'
            ])
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'label' => 'form.nft.groupe',
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
            'data_class' => Nft::class,
        ]);
    }
}
