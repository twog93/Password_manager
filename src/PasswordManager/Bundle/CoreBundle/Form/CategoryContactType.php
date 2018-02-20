<?php
/**
 * Created by PhpStorm.
 * User: duveau
 * Date: 20/02/18
 * Time: 11:03
 */

namespace PasswordManager\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryContactType  extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('motive');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PasswordManager\Bundle\CoreBundle\Entity\CategoryContact'
        ));
    }


}
