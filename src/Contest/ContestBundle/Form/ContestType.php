<?php

namespace Contest\ContestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContestType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add( 'title' )
            ->add( 'beginDate', 'datetimepicker', ['attr' => ['label' => 'Date de début'] ] )
            ->add( 'endDate', 'datetimepicker', ['attr' => ['label' => 'Date de fin'] ] )
            ->add( 'Créer mon concours', 'submit' );

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $contest = $event->getData();

            if( is_array( $contest->getBeginDate()) ){
                $contest->setBeginDate( $contest->getBeginDate()['date'] );
            }

            if( is_array( $contest->getEndDate()) ){
                $contest->setEndDate( $contest->getEndDate()['date'] );
            }

            $event->setData( $contest );
        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( [
            'data_class' => 'Contest\ContestBundle\Entity\Contest'
        ] );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'contest_type';
    }
}
