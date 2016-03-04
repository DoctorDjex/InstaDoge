<?php

namespace Contest\ContestBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TextToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (Datetime) to a string.
     *
     * @param \Datetime|null $date
     *
     * @return string
     */
    public function transform($date)
    {
        if (null === $date) {
            return '';
        }

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param string $stringDate
     *
     * @return \Datetime|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($stringDate)
    {
        // no issue number? It's optional, so that's ok
        if (!$stringDate) {
            return;
        }

        $date = \Datetime::createFromFormat('Y-m-d H:i:s', $stringDate);

        if (!$date) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                '%s n\'est pas une date valide !',
                $stringDate
            ));
        }

        return $date;
    }
}
