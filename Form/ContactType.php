<?php

namespace ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('descriptif', TextareaType::class)
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('emailsDestinataire', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('adresse', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('cp', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('country', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('fax', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('facebook', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('twitter', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('linkedin', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('googlePlus', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-circle-left',
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn red pull-right',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ContentBundle\Entity\Contact',
            'required' => false
        ]);
    }
    
}