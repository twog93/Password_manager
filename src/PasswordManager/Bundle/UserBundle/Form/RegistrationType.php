<?php
/**
 * Created by PhpStorm.
 * User: duveau
 * Date: 14/02/18
 * Time: 13:14
 */

namespace PasswordManager\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RegistrationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('groups',EntityType::class, array(
            'class' => 'PasswordManagerUserBundle:Group',
            'choice_label' => 'name',
            'label' =>'groupe',
            'multiple' =>false,

        ));

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

}