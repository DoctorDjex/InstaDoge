<?php

namespace Contest\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContestAdmin extends Admin
{
    protected $baseRouteName = 'contest_contest';

    protected $baseRoutePattern = 'contest';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text')
            ->add('beginDate', 'sonata_type_date_picker', array('format' => 'y-MM-dd HH:mm'))
            ->add('endDate', 'sonata_type_date_picker', array('format' => 'y-MM-dd HH:mm'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
            ->add('beginDate', 'doctrine_orm_datetime', array('field_type' => 'sonata_type_datetime_picker'))
            ->add('endDate', 'doctrine_orm_datetime', array('field_type' => 'sonata_type_datetime_picker'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title')->addIdentifier('beginDate')->addIdentifier('endDate');
    }
}
