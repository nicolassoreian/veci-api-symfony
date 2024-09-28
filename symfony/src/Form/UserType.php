<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type as CoreType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', CoreType\EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'form.placeholder.email',
                    'class' => 'form-control-lg shadow-none'
                ],
                'label_attr' => [
                    'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
                ],
                'label' => 'form.label.email',
                'required' => true,
                'constraints' => [
                    new Constraints\NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                    new Constraints\Email([
                        'message' => 'The email {{ value }} is not a valid email',
                    ])
                ],
            ])
            ->add('plainPassword', CoreType\RepeatedType::class, [
                'type' => CoreType\PasswordType::class,
                'label' => 'form.label.password',
                'first_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'form.placeholder.new_password',
                        'class' => 'form-control-lg shadow-none'
                    ],
                    'toggle' => true,
                    'hidden_label' => 'form.label.hide_password',
                    'visible_label' => 'form.label.show_password',
                    'label_attr' => [
                        'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
                    ],
                    'label' => 'form.label.new_password',
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'form.placeholder.repeat_password',
                        'class' => 'form-control-lg shadow-none'
                    ],
                    'toggle' => true,
                    'hidden_label' => 'form.label.hide_password',
                    'visible_label' => 'form.label.show_password',
                    'label_attr' => [
                        'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
                    ],
                    'label' => 'form.label.repeat_password'
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Constraints\Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('roles', CoreType\ChoiceType::class, [
                'placeholder' => 'form.placeholder.role',
                'attr' => [
                    'class' => 'form-control form-control-lg shadow-none'
                ],
                'label_attr' => [
                    'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
                ],
                'label' => 'form.label.role',
                'choices' => [
                    User::ROLE_ADMIN => User::ROLE_ADMIN
                ],
                'required' => true,
                'constraints' => [
                    new Constraints\NotBlank([
                        'message' => 'Please enter the new user role',
                    ]),
                ]
            ])
            ->add('enabled', CoreType\CheckboxType::class, [
                'label' => 'form.label.enabled',
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label_attr' => [
                    'class' => 'fw-semibold fs-6'
                ],
                'required' => false
            ])
            ->add('profile', UserProfileType::class, [
                'label' => false,
                'required' => true
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray) {
                    return count($rolesAsArray) ? $rolesAsArray[0]: null;
                },
                function ($rolesAsString) {
                    return [$rolesAsString];
                }
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();
            $entity = $event->getData();

            if (!$entity || null === $entity->getId()) {
                $form
                    ->add('plainPassword', CoreType\RepeatedType::class, [
                        'type' => CoreType\PasswordType::class,
                        'label' => 'form.label.password',
                        'first_options' => [
                            'attr' => [
                                'autocomplete' => 'new-password',
                                'placeholder' => 'form.placeholder.new_password',
                                'class' => 'form-control-lg shadow-none'
                            ],
                            'toggle' => true,
                            'hidden_label' => 'form.label.hide_password',
                            'visible_label' => 'form.label.show_password',
                            'label_attr' => [
                                'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
                            ],
                            'label' => 'form.label.new_password',
                            'hash_property_path' => 'password',
                            'required' => true
                        ],
                        'second_options' => [
                            'attr' => [
                                'placeholder' => 'form.placeholder.repeat_password',
                                'class' => 'form-control-lg shadow-none'
                            ],
                            'toggle' => true,
                            'hidden_label' => 'form.label.hide_password',
                            'visible_label' => 'form.label.show_password',
                            'label_attr' => [
                                'class' => 'd-flex align-items-center fs-6 fw-semibold mb-2'
                            ],
                            'label' => 'form.label.repeat_password',
                            'required' => true
                        ],
                        'mapped' => false,
                        'required' => true,
                        'constraints' => [
                            new Constraints\Length([
                                'min' => 8,
                                'minMessage' => 'Your password should be at least {{ limit }} characters',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
                            new Constraints\NotBlank([
                                'message' => 'Please enter a valid password',
                            ]),
                        ],
                    ])
                ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
