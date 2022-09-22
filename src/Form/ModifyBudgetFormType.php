<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Budget;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyBudgetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Category', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Courses'
                ],
                'label' => 'Nom de la catégorie',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('LimitAmount', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => '63.99€'
                ],
                'currency'=>'',
                'label' => 'Montant du budget',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-green',
                ],
                'label' => 'Modifier la catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Budget::class,
        ]);
    }
}
