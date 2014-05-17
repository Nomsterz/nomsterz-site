<?php
/**
 * Class ForgotForm
 *
 * filename:   ForgotForm.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/18/14 4:14 PM
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
 
class ForgotForm    extends Form
                    implements InputFilterAwareInterface
{
    public $forgot_email;

    protected $inputFilter;
    protected $forgot_csrf;

    public function __construct($name = null)
    {
        parent::__construct('ForgotLoginForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        // Email Address field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Email',
                        'name'          =>  'forgot_email',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'email',
                                                'id'        =>  'forgotFormReturnerField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Enter your Email address',
                                            ),
                    )
                );

        // CSRF field
        $csrfValidator      =   new CsrfValidator
                                    (
                                        array
                                        (
                                            'name'          =>  'forgot_csrf',
                                            'salt'          =>  'saltGoesHere',
                                            'timeout'       =>  600,
                                            'required'      =>  TRUE,
                                            'messages'      =>  array
                                                                (
                                                                    Validator\Csrf::NOT_SAME    =>  'Your forgotten login cannot be processed at this time. Please refresh the page or contact Tech Support at tech_support@nomsterz.com',
                                                                )
                                        )
                                    );
        $ForgotCsrf         =   new CsrfElement('forgot_csrf');
        $ForgotCsrf->setAttribute('id', 'ForgotFormCsrfField');
        $ForgotCsrf->setCsrfValidator($csrfValidator);
        $this->add($ForgotCsrf);

        //assign!
        $this->forgot_csrf  =   $ForgotCsrf;
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
                                                    'name'          =>  'forgot_email',
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
                                                    'name'          =>  'forgot_csrf',
                                                    'required'      =>  true,
                                                    'validators'    =>  array
                                                                        (
                                                                            $this->forgot_csrf->getCsrfValidator(),
                                                                        ),
                                                )
                                            )
                            );

            $this->inputFilter  =   $inputFilter;
        }

        return $this->inputFilter;
    }
}
