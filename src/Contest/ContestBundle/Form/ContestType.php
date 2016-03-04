<?php

namespace Contest\ContestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('beginDate', 'datetimepicker',
                ['attr' => ['label' => 'Date de début'],
                  'invalid_message' => 'La date de début n\'est pas valide !', ])
            ->add('endDate', 'datetimepicker',
                ['attr' => ['label' => 'Date de fin'],
                  'invalid_message' => 'La date de fin n\'est pas valide !', ])
            ->add('Créer mon concours', 'submit');

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $contest = $event->getData();

            $beginDate = $contest->getBeginDate();
            if (is_array($beginDate) && isset($beginDate['date'])) {
                $contest->setBeginDate($beginDate['date']);
            }

            $endDate = $contest->getEndDate();
            if (is_array($endDate) && isset($endDate['date'])) {
                $contest->setEndDate($endDate['date']);
            }

            $event->setData($contest);
        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Contest\ContestBundle\Entity\Contest',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contest_type';
    }
}
