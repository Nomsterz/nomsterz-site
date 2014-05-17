<?php
/**
 * Class PasswordStrength
 *
 * filename:   PasswordStrength.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/2/14 8:34 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Form\Validator;

use Zend\Validator\AbstractValidator;

class PasswordStrength extends AbstractValidator
{
    const INVALID  = 'invalid';

    protected $messageTemplates     =   array
                                        (
                                            self::INVALID  => "Password must have at least 10 characters, contain at least 1 lower letter, 1 upper letter and a number."
                                        );

    public function isValid($value)
    {
        $this->setValue($value);

        $isValid = true;

        if (!preg_match('/[A-Z]/', $value))
        {
            $this->error(self::INVALID);
            $isValid = false;
            return $isValid;
        }

        if (!preg_match('/[a-z]/', $value))
        {
            $this->error(self::INVALID);
            $isValid = false;
            return $isValid;
        }

        if (!preg_match('/\d/', $value))
        {
            $this->error(self::INVALID);
            $isValid = false;
            return $isValid;
        }

        return $isValid;

    }
}
