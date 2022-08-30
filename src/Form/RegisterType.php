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
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: Matthieu'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: Piquemal'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: matthieu.piquemal@gmail.com'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe rentrés doivent être indentiques.',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe', 
                    'attr' => [
                        'placeholder' => 'Ex: mattHieu53!'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe', 
                    'attr' => [
                        'placeholder' => 'Ex: mattHieu53!'
                    ]
                ],  
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
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
