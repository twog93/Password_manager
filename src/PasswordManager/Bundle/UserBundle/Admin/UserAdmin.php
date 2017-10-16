<?php

namespace PasswordManager\Bundle\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends AbstractAdmin
{
	
	 protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('groups','sonata_type_model', array(
                'class' => 'PasswordManager\Bundle\UserBundle\Entity\Group',
                'multiple' => true,
                'property' => 'name'

            ))
            ->add('enabled')
        ;

        if ($this->isCurrentRoute('edit')) {
            $formMapper->remove('password');
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('username');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('username')
            ->addIdentifier('roles')
            ->addIdentifier('email')
            ->addIdentifier('enabled')
            ->addIdentifier('group')

        ;
    }
	
	
	
}
