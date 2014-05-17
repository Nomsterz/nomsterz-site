<?php
/**
 * Class ChangePasswordWithVerifyLinkForm
 *
 * filename:   ChangePasswordWithVerifyLinkForm.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/27/14 7:13 PM
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

class ChangePasswordWithVerifyLinkForm  extends Form
                    					implements InputFilterAwareInterface
{
    public $change_verify_member;
    public $vcode;
    public $password;
    public $cpassword;
    public $acceptTerms;

    protected $inputFilter;
    protected $change_pwd_verify_csrf;

    public function __construct($name = null)
    {
        parent::__construct('ChangePasswordWithVerifyLinkForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        // Input Email Address field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Email',
                        'name'          =>  'change_verify_member',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'email',
                                                'id'        =>  'ChangePasswordWithVerifyLinkFormChangeVerifyMemberField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label'     =>  'Email address',
                                            ),
                    )
                );

        // Vcode field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Hidden',
                        'name'          =>  'vcode',
                        'attributes'    =>  array
                                            (
                                                'id'            =>  'ChangePasswordWithVerifyLinkFormVcodeField',
                                                'class'         =>  'form-control',
                                            ),
                    )
                );

        // CSRF field
        $csrfValidator      =   new CsrfValidator
                                    (
                                        array
                                        (
                                            'name'          =>  'change_verify_csrf',
                                            'salt'          =>  'saltGoesHere',
                                            'timeout'       =>  600,
                                            'required'      =>  TRUE,
                                            'messages'      =>  array
                                                                (
                                                                    Validator\Csrf::NOT_SAME    =>  'Your password change cannot be processed at this time. Please refresh the page or contact Tech Support at tech_support@nomsterz.com',
                                                                )
                                        )
                                    );
        $ChangePasswordVerifyCsrf         =   new CsrfElement('change_verify_csrf');
        $ChangePasswordVerifyCsrf->setAttribute('id', 'ChangePasswordWithVerifyLinkFormCsrfField');
        $ChangePasswordVerifyCsrf->setCsrfValidator($csrfValidator);
        $this->add($ChangePasswordVerifyCsrf);

        //assign!
        $this->change_verify_csrf  =   $ChangePasswordVerifyCsrf;

        // Password
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Password',
                        'name'          =>  'password',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'password',
                                                'id'        =>  'ChangePasswordWithVerifyLinkFormPasswordField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'New Password',
                                            ),
                    )
                );

        // Confirm Password
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Password',
                        'name'          =>  'cpassword',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'password',
                                                'id'        =>  'ChangePasswordWithVerifyLinkFormConfirmedPasswordField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Repeat New Password',
                                            ),
                    )
                );

        // Terms & Privacy Policy Checkbox
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Checkbox',
                        'name'          =>  'acceptTerms',
                        'required'      =>  TRUE,
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'checkbox',
                                                'id'        =>  'ChangePasswordWithVerifyLinkFormTermsBox',
                                                'class'     =>  'uniform',
                                            ),
                        'options'       =>  array
                                            (
                                                'label'                 =>  'I agree to the <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a>',
                                                'use_hidden_element'    =>  true,
                                                'checked_value'         =>  '1',
                                                'unchecked_value'       =>  '',
                                                'label_attributes'      =>  array
                                                                            (
                                                                                'class'     =>  'checkbox',
                                                                            ),
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

            // Input Email Address field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'change_verify_member',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'The email address you received your verification message at is required and can not be empty.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'The email address you received your verification message at is required and can not be empty.',
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
                                                                                                                        Validator\EmailAddress::INVALID         =>  'This email address (%value%) is invalid.',
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

            // Vcode field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'vcode',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Your Verified Password Change cannot be processed at this time. Please go back to your inbox and click the link or contact Tech Support at tech_support@nomsterz.com',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Your Verified Password Change cannot be processed at this time. Please go back to your inbox and click the link or contact Tech Support at tech_support@nomsterz.com',
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
                                                    'name'          =>  'change_verify_csrf',
                                                    'required'      =>  true,
                                                    'validators'    =>  array
                                                                        (
                                                                            $this->change_verify_csrf->getCsrfValidator(),
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
                                                    'name'          =>  'password',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Please enter your new password.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Please enter your new password.',
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
                                                                                                                        Validator\StringLength::INVALID     =>  'Passwords must be more than 10 digits. Valid characters only.',
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

            // Confirm Password
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'cpassword',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Please confirm your new password.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Please confirm your new password.',
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
                                                                                                                        Validator\StringLength::INVALID     =>  'Confirmed Passwords must be more than 10 digits. Valid characters only.',
                                                                                                                        Validator\StringLength::TOO_SHORT   =>  'Confirmed Password is too short.',
                                                                                                                        Validator\StringLength::TOO_LONG    =>  'Confirmed Password is too long.',
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
                                                                                                                        CustomValidator\PasswordStrength::INVALID     =>  'Confirmed Password must have at least 10 characters, contain at least 1 lower letter, 1 upper letter and a number.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Identical',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'token'     =>  'password',
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Identical::NOT_SAME    =>  'Passwords do not match.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            // Terms & Privacy Policy Checkbox
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'acceptTerms',
                                                    'required'      =>  TRUE,
                                                    'filters'       =>  array
                                                                        (

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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Please indicate that you read our Terms & Privacy Policy.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Please indicate that you read our Terms & Privacy Policy.',
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
