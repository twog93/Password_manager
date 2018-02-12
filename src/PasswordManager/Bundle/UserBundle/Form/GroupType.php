<?php

namespace PasswordManager\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PasswordManager\Bundle\PlatformBundle\Entity\CategoryRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use PasswordManager\Bundle\UserBundle\Entity;
use FOS\UserBundle\Form\Type\UsernameFormType;


class GroupType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		 
        $builder
        ->add('name')
        ->add('roles')
        ->add('save', SubmitType::class);

    }
    



}
