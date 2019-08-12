<?php

namespace AppBundle\Utils;

use Symfony\Component\Validator\ConstraintViolation;


class Utils
{
    /**
     * @param $errors
     * @return array
     */
    public static function getErrors($errors)
    {
        $err = [];

        foreach ($errors as $error) {
            /**
             * @var ConstraintViolation $error
             */
            if (!isset($err[$error->getPropertyPath()])) {
                $err[$error->getPropertyPath()] = [];
            }
            $err[$error->getPropertyPath()][] = $error;
        }

        return $err;
    }
}
