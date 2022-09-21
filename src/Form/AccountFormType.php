<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class,[
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Compte courant'
                ],
                'label' => 'Intitulé',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('Bank', TextType::class,[
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Crédit-Agricole'
                ],
                'label' => 'Banque',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('Balance', MoneyType::class,[
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => '63.99€'
                ],
                'currency'=>'',
                'label' => 'Solde (en €)',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-green',
                ],
                'label' => 'Créer le compte',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
