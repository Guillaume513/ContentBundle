<?php

namespace ContentBundle\Form;

use ContentBundle\Repository\RubriqueRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $rub = $builder->getData()->getRubrique();

        $builder
            ->add('isActive', CheckboxType::class, [
                'label'    => ' ',
                'required' => false,
            ])
            ->add('orderNbr', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control input-xs rounded',
                    'min'=>1,
                    'max'=>50
                ],
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ],

            ])
            ->add('subtitle', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ],

            ])
            ->add('chapo', CKEditorType::class, [
                'config_name' => 'my_config',
            ])
            ->add('summary', CKEditorType::class, [
                'config_name' => 'my_config',
            ])
            ->add('text', CKEditorType::class, [
                'config_name' => 'my_config',
            ])
            ->add('document', CollectionType::class, [
                'entry_type' => DocumentContentType::class,
                'by_reference' => true,
            ])
            ->add('rubrique', EntityType::class, [
                'class' => 'ContentBundle\Entity\Rubrique',
                'choice_label' => 'title',
                'data' => $rub,
                'query_builder' => function(RubriqueRepository $repo) {
                    $qb = $repo->createQueryBuilder('c')
                        ->andWhere('c.isActive = :active')
                        ->setParameter('active', 1);
                    return $qb;
                },
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('refUrl', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ]
            ])
            ->add('refTitle', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ]
            ])
            ->add('refSummary', CKEditorType::class, [
                'config_name' => 'my_config',
            ])
            ->add('refKeywords', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ]
            ])
            ->add('tabulation', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'tabulation513',
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary pull-right margin-right-10 push-inverse-top-30',
                ]
            ]);
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ContentBundle\Entity\Page',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'page_item',
            'required' => false
        ]);
    }
}
