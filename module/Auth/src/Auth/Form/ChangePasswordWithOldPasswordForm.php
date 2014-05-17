<?php
/**
 * Class ChangePasswordWithOldPasswordForm
 *
 * filename:   ChangePasswordWithOldPasswordForm.php
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

class ChangePasswordWithOldPasswordForm    	extends Form
                    						implements InputFilterAwareInterface
{
    public $change_old_member;
    public $password;
    public $new_password;
    public $cnew_password;
    public $acceptTerms;

    protected $inputFilter;
    protected $change_pwd_old_csrf;

    public function __construct($name = null)
    {
        parent::__construct('ChangePasswordWithOldPasswordForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        // Email Address field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Email',
                        'name'          =>  'change_old_member',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'email',
                                                'id'        =>  'ChangePasswordWithOldPasswordFormChangeOldMemberField',
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
                                            'name'          =>  'change_pwd_old_csrf',
                                            'salt'          =>  'saltGoesHere',
                                            'timeout'       =>  600,
                                            'required'      =>  TRUE,
                                            'messages'      =>  array
                                                                (
                                                                    Validator\Csrf::NOT_SAME    =>  'Your password change cannot be processed at this time. Please refresh the page or contact Tech Support at tech_support@nomsterz.com',
                                                                )
                                        )
                                    );
        $CSRFObject         =   new CsrfElement('change_pwd_old_csrf');
        $CSRFObject->setAttribute('id', 'ChangePasswordWithOldPasswordFormCsrfField');
        $CSRFObject->setCsrfValidator($csrfValidator);
        $this->add($CSRFObject);

        //assign!
        $this->change_pwd_old_csrf  =   $CSRFObject;

        // Old Password
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Password',
                        'name'          =>  'password',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'password',
                                                'id'        =>  'ChangePasswordWithOldPasswordFormPasswordField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Your Current Password',
                                            ),
                    )
                );

        // New Password
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Password',
                        'name'          =>  'new_password',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'password',
                                                'id'        =>  'ChangePasswordWithOldPasswordFormNewPasswordField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Choose A New Password',
                                            ),
                    )
                );

        // Confirm New Password
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Password',
                        'name'          =>  'cnew_password',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'password',
                                                'id'        =>  'ChangePasswordWithOldPasswordFormConfirmedNewPasswordField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Confirm Your New Password',
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
                                                'id'        =>  'ChangePasswordWithOldPasswordFormTermsBox',
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

            // Email Address field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'change_old_member',
                                                    'required'      => 	true,
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
                                                    'name'          =>  'change_pwd_old_csrf',
                                                    'required'      =>  true,
                                                    'validators'    =>  array
                                                                        (
                                                                            $this->change_pwd_old_csrf->getCsrfValidator(),
                                                                        ),
                                                )
                                            )
                            );

            // Old Password
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

            // New Password
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'new_password',
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
                                                                                                                        Validator\StringLength::INVALID     =>  'New Passwords must be more than 10 digits. Valid characters only.',
                                                                                                                        Validator\StringLength::TOO_SHORT   =>  'New Password is too short.',
                                                                                                                        Validator\StringLength::TOO_LONG    =>  'New Password is too long.',
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
                                                                                                                        CustomValidator\PasswordStrength::INVALID     =>  'New Password must have at least 10 characters, contain at least 1 lower letter, 1 upper letter and a number.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            )
                                                                        ),
                                                )
                                            )
                            );

            // Confirm New Password
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'cnew_password',
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
                                                                                                                        Validator\StringLength::INVALID     =>  'Confirmed New Passwords must be more than 10 digits. Valid characters only.',
                                                                                                                        Validator\StringLength::TOO_SHORT   =>  'Confirmed New Password is too short.',
                                                                                                                        Validator\StringLength::TOO_LONG    =>  'Confirmed New Password is too long.',
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
                                                                                                                        CustomValidator\PasswordStrength::INVALID     =>  'Confirmed New Password must have at least 10 characters, contain at least 1 lower letter, 1 upper letter and a number.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Identical',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'token'     =>  'new_password',
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Identical::NOT_SAME    =>  'Confirmed New Password does not match New Password.',
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
