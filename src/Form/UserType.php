<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'attr' => [
                        'placeholder' => 'Введите имя'
                ],
                //'Placeholder' => 'Введите имя',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('familyName', TextType::class, [
                'label' => 'Фамилия',
                'attr' => [
                        'placeholder' => 'Введите фамилию'
                ],
                'required' => false,
                'empty_data' => ''
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'attr' => [
                        'placeholder' => 'Введите телефон'
                ],
                'required' => false,
                'empty_data' => ''
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                        'placeholder' => 'Введите свой email'
                ],
                'required' => true,
            ])

            ->add('birthDate', DateType::class, [
                'required' => false,
                'by_reference' => true,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
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
