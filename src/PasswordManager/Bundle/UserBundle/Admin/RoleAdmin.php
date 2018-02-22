<?php

namespace PasswordManager\Bundle\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
Use PasswordManager\Bundle\UserBundle\Entity\Group;

class RoleAdmin extends AbstractAdmin
{

    public function getNewInstance()
    {
        return new Group('');
    }
	
	 protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name');

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name');
    }
	
	
	
}
