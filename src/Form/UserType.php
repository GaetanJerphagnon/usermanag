<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Valid;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'constraints' => [
                    new Valid\NotBlank(),
                ],
            ])
            ->add('lastname', null, [
                'constraints' => [
                    new Valid\NotBlank(),
                ],
            ])
            ->add('email', Type\EmailType::class, [
                'constraints' => [
                    new Valid\Email(),
                ],
            ])
            ->add('password', Type\PasswordType::class, [
                'mapped' => false,
            ])
            ->add('avatar')
            ->add('birthday', null, [
                'widget' => 'single_text',
                'format' => 'yyyy-mm-dd',
                'html5' => false,
                'constraints' => [
                    new Valid\NotBlank(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
