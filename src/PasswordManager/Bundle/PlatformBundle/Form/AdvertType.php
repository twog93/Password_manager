<?php

namespace PasswordManager\Bundle\PlatformBundle\Form;


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
use PasswordManager\Bundle\UserBundle\Repository\GroupRepository;


class AdvertType extends AbstractType
{



    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $currentUser = $options['currentUser'];
        $group = $currentUser->getGroups()->getValues();
        $pattern = array();
        foreach ($group as $key => $value) {

            $pattern[] = $value->getName() ;
        }

        dump($pattern);

        $builder
        ->add('title')
        ->add('url')
        ->add('login')
        ->add('password')
        ->add('content')
  ->add('groups',EntityType::class, array(
                'class' => 'PasswordManagerUserBundle:Group',
                'choice_label' => 'name',
                'label' =>'Partagé dans mon groupe',
      'preferred_choices' => array('Sans groupe'),
                'multiple' =>true,
         //query_builder' => function(GroupRepository $repository){return $repository->getGroupWithUser();}
            'query_builder' => function(GroupRepository $repository) use ($pattern){
                    return $repository->getGroupWithUser($pattern);
                                 }

            ))
		   ->add('categories',EntityType::class, array(
            'class' => 'PasswordManagerPlatformBundle:Category',
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => false,
            'required' => true,
            ))
        ->add('shared', CheckboxType::class,array(
            'label' => 'Partagé ce mot de passe au groupe',
            'required' => false,

        ))
        ->add('save', SubmitType::class,array('label' => 'Ajouté ce mot de passe'));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //$resolver->setRequired('currentUser');
        $resolver->setDefaults(array(
            'data_class' => 'PasswordManager\Bundle\PlatformBundle\Entity\Advert',
            'currentUser' => '$user'
        ));


    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'passwordmanager_bundle_platformbundle_advert';
    }


}
