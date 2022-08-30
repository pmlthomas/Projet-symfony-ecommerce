<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('lastName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon nom'
            ])
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon email'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe rentrés doivent être indentiques.',
                'required' => true,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe', 
                    'attr' => [
                        'placeholder' => 'Ex: pauL504'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer mon nouveau mot de passe', 
                    'attr' => [
                        'placeholder' => 'Ex: pauL504'
                    ]
                ],  
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer les modifications"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
