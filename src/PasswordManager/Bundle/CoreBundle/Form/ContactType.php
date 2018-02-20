<?php

namespace PasswordManager\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

//Form contact Type
class ContactType extends AbstractType
{

    protected $username;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

	//add 2 inputs for model
        $builder
            ->add('categorieContact',EntityType::class, array(
                'class' => 'PasswordManagerCoreBundle:CategoryContact',
                'choice_label' => 'motive',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
        ->add('subject')
        ->add('body')

        ->add('save', SubmitType::class);

    }
}

