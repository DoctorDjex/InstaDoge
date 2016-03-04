<?php

namespace Contest\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ImageAdmin extends Admin
{
    protected $baseRouteName = 'contest_image';

    protected $baseRoutePattern = 'image';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text')
            ->add('contest', 'sonata_type_model_autocomplete', array('property' => 'title'))
            ->add('description', 'textarea')
            ->add('file', 'file', array('data_class' => null));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
            ->add('description')
            ->add('path');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title')->addIdentifier('description')->addIdentifier('path');
    }
}
