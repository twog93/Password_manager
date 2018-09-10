<?php

namespace PasswordManager\Bundle\PlatformBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PasswordAdmin extends AbstractAdmin
{
	
	 protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('url')
            ->add('login')
            ->add('password')
            ->add('content')
            ->add('categories','sonata_type_model', array(
                'class' => 'PasswordManager\Bundle\PlatformBundle\Entity\Category',
                'multiple' => true,
                'property' => 'name'

            ));

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('url')
            ->add('login');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->addIdentifier('url')
        ;
    }
	
	
	
}
