<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as CoreType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class LoginUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('email', CoreType\EmailType::class, [
            'mapped' => false,
            'required' => true,
            'row_attr' => [
              'class' => 'mb-4',
            ],
            'attr' => [
                'placeholder' => 'form.placeholder.email',
                'class' => 'form-control-lg form-control-solid shadow-none',
                'autofocus' => 'autofocus',
                'tabindex' => 1,
            ],
            'label_attr' => [
                'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
            ],
            'label' => 'form.label.email',
            'constraints' => [
                new Constraints\NotBlank([
                    'message' => 'Please enter your email',
                ]),
                new Constraints\Email([
                    'message' => 'The email {{ value }} is not a valid email',
                ])
            ],
        ])
        ->add('password', CoreType\PasswordType::class, [
            'toggle' => true,
            'mapped' => false,
            'required' => true,
            'attr' => [
                'autocomplete' => 'new-password',
                'placeholder' => 'form.placeholder.password',
                'class' => 'form-control-lg form-control-solid shadow-none',
                'tabindex' => 2,
            ],
            'label_attr' => [
                'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
            ],
            'label' => 'form.label.password',
            'toggle' => true,
            'hidden_label' => 'form.label.hide_password',
            'visible_label' => 'form.label.show_password',
            'constraints' => [
                new Constraints\NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Constraints\Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
