<?php

namespace ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'attr'       => [
                    'capture' => true,
                    'accept'  => 'image/*',
                    'multiple' => 'multiple',
                    'class' => 'not-display',
                ],
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ],
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ContentBundle\Entity\DocumentContent',
            'required' => false,
        ]);
    }
}
