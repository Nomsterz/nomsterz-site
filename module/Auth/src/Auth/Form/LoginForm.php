<?php
/**
 * Class LoginForm
 *
 * filename:   LoginForm.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/18/14 4:13 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;
use Auth\Form\Validator as CustomValidator;

class LoginForm     extends Form
                    implements InputFilterAwareInterface
{
    public $returning_member;
    public $password;
    public $rememberMe;

    protected $inputFilter;
    protected $login_csrf;

    public function __construct($name = null)
    {
        parent::__construct('LoginForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        // Email Address field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Email',
                        'name'          =>  'returning_member',
                        'required'      =>  TRUE,
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'email',
                                                'id'        =>  'LoginFormReturningMemberField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label'     =>  'Email address',
                                            ),
                    )
                );

        // CSRF field
        $csrfValidator      =   new CsrfValidator
                                    (
                                        array
                                        (
                                            'name'          =>  'login_csrf',
                                            'salt'          =>  'saltGoesHere',
                                            'timeout'       =>  600,
                                            'required'      =>  TRUE,
                                            'messages'      =>  array
                                                                (
                                                                    Validator\Csrf::NOT_SAME    =>  'Your login cannot be processed at this time. Please refresh the page or contact Tech Support at tech_support@nomsterz.com',
                                                                )
                                        )
                                    );
        $LoginCsrf         =   new CsrfElement('login_csrf');
        $LoginCsrf->setAttribute('id', 'LoginFormCsrfField');
        $LoginCsrf->setCsrfValidator($csrfValidator);
        $this->add($LoginCsrf);

        //assign!
        $this->login_csrf  =   $LoginCsrf;

        // Password
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Password',
                        'name'          =>  'LoginFormPasswordField',
                        'required'      =>  TRUE,
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'password',
                                                'id'        =>  'LoginFormPasswordField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Password',
                                            ),
                    )
                );
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter)
        {
            $inputFilter    =   new InputFilter();
            $factory        =   new InputFactory();

            // Email Address field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'returning_member',
                                                    'required'      => true,
                                                    'filters'       =>  array
                                                                        (
                                                                            array
                                                                            (
                                                                                'name'      =>  'StripTags',
                                                                                'options'   =>  array
                                                                                                (

                                                                                                ),
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'StringTrim',
                                                                                'options'   =>  array
                                                                                                (

                                                                                                ),
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'StripNewLines',
                                                                                'options'   =>  array
                                                                                                (

                                                                                                ),
                                                                            ),
                                                                        ),
                                                    'validators'    =>  array
                                                                        (
                                                                            array
                                                                            (
                                                                                'name'      =>  'NotEmpty',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\NotEmpty::INVALID     =>  'An email address is required and can not be empty.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'An email address is required and can not be empty.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'EmailAddress',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'encoding'  =>  'UTF-8',
                                                                                                    'min'       =>  5,
                                                                                                    'max'       =>  255,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\EmailAddress::INVALID         =>  'The email address (%value%) is invalid.',
                                                                                                                        Validator\EmailAddress::INVALID_FORMAT  =>  'Email address format is invalid.',
                                                                                                                        Validator\EmailAddress::QUOTED_STRING   =>  'Email address is quoted.',
                                                                                                                        Validator\EmailAddress::LENGTH_EXCEEDED =>  'Email address is too long.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            // CSRF field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'login_csrf',
                                                    'required'      =>  true,
                                                    'validators'    =>  array
                                                                        (
                                                                            $this->login_csrf->getCsrfValidator(),
                                                                        ),
                                                )
                                            )
                            );

            // Password
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'LoginFormPasswordField',
                                                    'required'      =>  TRUE,
                                                    'filters'       =>  array
                                                                        (
                                                                            array
                                                                            (
                                                                                'name'      =>  'StripTags',
                                                                                'options'   =>  array
                                                                                                (

                                                                                                ),
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'StringTrim',
                                                                                'options'   =>  array
                                                                                                (

                                                                                                ),
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'StripNewLines',
                                                                                'options'   =>  array
                                                                                                (

                                                                                                ),
                                                                            ),
                                                                        ),
                                                    'validators'    =>  array
                                                                        (
                                                                            array
                                                                            (
                                                                                'name'      =>  'NotEmpty',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Please enter your password.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Please enter your password.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'StringLength',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'encoding'  =>  'UTF-8',
                                                                                                    'min'       =>  10,
                                                                                                    'max'       =>  256,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\StringLength::INVALID     =>  'Passwords must be more than 5 digits. Valid characters only.',
                                                                                                                        Validator\StringLength::TOO_SHORT   =>  'Password is too short.',
                                                                                                                        Validator\StringLength::TOO_LONG    =>  'Password is too long.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Auth\Form\Validator\PasswordStrength',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        CustomValidator\PasswordStrength::INVALID     =>  'Password must have at least 10 characters, contain at least 1 lower letter, 1 upper letter and a number.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            $this->inputFilter  =   $inputFilter;
        }

        return $this->inputFilter;
    }
}
