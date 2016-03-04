<?php

namespace Contest\ContestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchContestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', searchType::class, ['label' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contest_search_contest';
    }
}
