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
use FOS\UserBundle\Model\UserInterface;

class AdvertType extends AbstractType
{



    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Arbitrairement, on récupère toutes les catégories qui commencent par "D"

        dump($options);
        $pattern = 6;

        $builder
        ->add('title')
        ->add('url')
        ->add('login')
        ->add('password')
        ->add('content')
  ->add('groups',EntityType::class, array(
                'class' => 'PasswordManagerUserBundle:Group',
                'choice_label' => 'name',
                'multiple' =>true,
         //query_builder' => function(GroupRepository $repository){return $repository->getGroupWithUser();}
            'query_builder' => function(GroupRepository $repository) use ($pattern){
                    return $repository->getGroupWithUser($pattern);
                                 }

            ))
            /*


       * Rappel :
       ** - 1er argument : nom du champ,
       ** - 2e argument : type du champ,
       ** - 3e argument : tableau d'options du champ.

	   	->add('categories',  EntityType::class, array(

        'class'         => 'PasswordManagerPlatformBundle:Category',

        'choice_label'  => 'name',

        'multiple'      => true,

        'query_builder' => function(CategoryRepository $repository) use($pattern) {

          return $repository->getLikeQueryBuilder($pattern);

        }

      ))

       */
		   ->add('categories',EntityType::class, array(
            'class' => 'PasswordManagerPlatformBundle:Category',
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'required' => true,
            ))
        ->add('save', SubmitType::class);

        // On ajoute une fonction qui va écouter un évènement
       /* $builder->addEventListener(
            FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
            function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
                // On récupère notre objet Advert sous-jacent
                $advert = $event->getData();

                // Cette condition est importante, on en reparle plus loin
                if (null === $advert) {
                    return; // On sort de la fonction sans rien faire lorsque $advert vaut null
                }

                // Si l'annonce n'est pas publiée, ou si elle n'existe pas encore en base (id est null)
                if (!$advert->getPublished() || null === $advert->getId()) {
                    // Alors on ajoute le champ published
                    $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                } else {
                    // Sinon, on le supprime
                    $event->getForm()->remove('published');
                }
            }
        );*/
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PasswordManager\Bundle\PlatformBundle\Entity\Advert'
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
