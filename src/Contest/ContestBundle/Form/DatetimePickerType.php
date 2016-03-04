<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 02/03/2016
 * Time: 10:45.
 */

namespace Contest\ContestBundle\Form;

use Contest\ContestBundle\Form\Transformer\TextToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatetimePickerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'text', ['attr' => ['class' => 'datetimepicker',
                                                           'invalid_message' => 'Cette date n\'est pas valide !', ],
                                     'empty_data' => new \DateTime(),
                                     'error_bubbling' => true, ]);

        $builder->get('date')
            ->addModelTransformer(new TextToDateTimeTransformer());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datetimepicker';
    }
}
