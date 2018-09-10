<?php

namespace PasswordManager\Bundle\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PasswordManager\Bundle\PlatformBundle\Entity\CategoryRepository;

class AdvertEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		 $builder->remove('image')
        ->add('save', SubmitType::class,array('label' => 'Modifier mon mot de passe'));
		
    }
	
	 public function getParent()

  {
    return AdvertType::class;

  }

}
