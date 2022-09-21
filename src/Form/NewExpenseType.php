<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Expense;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class NewExpenseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $budgets = $options['budgets'];
        $builder
            ->add('Movement', ChoiceType::class, [
                'choices' => array(
                    'Dépense' => 'expense',
                    'Recette' => 'revenue',
                ),
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                'label' => 'Type de mouvement',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'mapped' => false,
            ])
            ->add('Date', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control mb-2',
                ],
                'data' => new \DateTime(),
                'label' => 'Date',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('Location', TextType::class,[
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'IKEA'
                ],
                'label' => 'Lieu',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('Detail', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Chaise de bureau'
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('Amount', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => '63.99€'
                ],
                'currency'=>'',
                'label' => 'Montant (en €)',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])

            ->add('Budget', EntityType::class, [
                'class' => Budget::class,
                'choice_label' => 'Category',
                'choices' => $budgets,
                'attr' => [
                    'class' => 'form-control mb-2',
                ],
                'label' => 'Catégorie',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-green',
                ],
                'label' => 'Créer la dépense',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
            'budgets' => null,
        ]);
    }
}
