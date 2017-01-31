<?php

namespace AppBundle\Form;

use ContentBundle\Entity\Rubrique;
use ContentBundle\Repository\PageRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $article = $builder->getData()->getPage();

        $rub = $article->getRubrique();

        $builder
            ->add('isActive', CheckboxType::class, [
                'label'    => ' ',
                'required' => false,
            ])
            ->add('orderNbr', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                    'min'=>1,
                    'max'=>50
                ],
                'required' => false,
            ])
            ->add('subhead', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ],
                'required' => false
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ],
                'required' => false
            ])
            ->add('subtitle', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded',
                ],
                'required' => false
            ])
            ->add('chapo', CKEditorType::class, [
                'required' => false,
                'config_name' => 'my_config',
            ])
            ->add('summary', CKEditorType::class, [
                'required' => false,
                'config_name' => 'my_config',
            ])
            ->add('text', CKEditorType::class, [
                'required' => false,
                'config_name' => 'my_config',
            ])
            ->add('documentContent', CollectionType::class, [
                'entry_type' => DocumentContentType::class,
                'by_reference' => true,
            ])
            ->add('page', EntityType::class, [
                'class' => 'ContentBundle\Entity\Page',
                'choice_label' => 'title',
                'query_builder' => function(PageRepository $repo) use ($rub) {
                    $qb = $repo->createQueryBuilder('c')
                        ->andWhere('c.isActive = :active')
                        ->setParameter('active', 1);
                    if ($rub instanceof Rubrique) {
                        $qb
                            ->andWhere('c.rubrique = :rubrique')
                            ->setParameter('rubrique', $rub->getId());
                    }
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
                'required' => false,
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ContentBundle\Entity\Article',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'article_item',
            'required' => false,
        ]);
    }
}
