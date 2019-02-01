<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, array('attr' => array('placeholder' => 'Nom d\'utilisateur',
                                                                                'class' => 'form-control '
            )))
            ->add('password',PasswordType::class, array('attr' => array('placeholder' => 'Mot de passe',
                                                                                     'class' => 'form-control'
            )))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Email',
                'class' => 'form-control')))
            ->add('type',TextType::class, [
                    'disabled' => true,
                    'attr' => array(   'class' => 'form-control', 'value' => 'member')
            ])
            ->add('photo', FileType::class, array('attr' => array('required' => false,
                'class' => 'form-control')))
            ->add('phone', NumberType::class, array('attr' => array('placeholder' => 'Tél',
                'class' => 'form-control')))
            ->add('isActive')
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ],

            ])

            ->add('save', SubmitType::class, array('label' =>'Enregister',
                                                               'attr' => array( 'class' => 'btn btn-primary')))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
