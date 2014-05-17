<?php
/**
 * Class VerificationDetailsForm
 *
 * filename:   VerificationDetailsForm.php
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
use Zend\I18n\Validator as I18nValidator;
use Zend\Validator;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;
 
class VerificationDetailsForm    extends Form
                    implements InputFilterAwareInterface
{
    public $first_name;
    public $last_name;
    public $gender;
    public $member_type;
    public $zip_code;
    public $time_zone;

    protected $inputFilter;
    protected $verification_details_csrf;

    public function __construct($name = null)
    {
        parent::__construct('VerificationDetailsForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        // First Name field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Text',
                        'name'          =>  'first_name',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'text',
                                                'id'        =>  'VerificationDetailsFormFirstNameField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'First Name',
                                            ),
                    )
                );

        // Last Name field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Text',
                        'name'          =>  'last_name',
                        'attributes'    =>  array
                                            (
                                                'type'      =>  'text',
                                                'id'        =>  'VerificationDetailsFormLastNameField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label' => 'Last Name',
                                            ),
                    )
                );

        // Gender field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Select',
                        'name'          =>  'gender',
                        'attributes'    =>  array
                                            (
                                                'id'        =>  'VerificationDetailsFormGenderField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label'         =>  'Gender',
                                                'empty_option'  =>  'Please choose...',
                                                'value_options' =>  array
                                                                    (
                                                                        '1' => 'Female',
                                                                        '2' => 'Male',
                                                                    ),
                                            )
                    )
                );

        // Member Type field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Select',
                        'name'          =>  'member_type',
                        'attributes'    =>  array
                                            (
                                                'id'        =>  'VerificationDetailsFormMemberTypeField',
                                                'class'     =>  'form-control',
                                            ),
                        'options'       =>  array
                                            (
                                                'label'         =>  'Which are you?',
                                                'empty_option'  =>  'Please choose...',
                                                'value_options' =>  array
                                                                    (
                                                                        '1' => 'Business',
                                                                        '2' => 'Signing Agent',
                                                                        '3' => 'Lender',
                                                                        '4' => 'Client',
                                                                    ),
                                            )
                    )
                );

        // Zip Code field
        $this->add
                (
                    array
                    (
                        'type'          =>  'Zend\Form\Element\Text',
                        'name'          =>  'zipcode',
                        'attributes'    =>  array
                                            (
                                                'id'            =>  'VerificationDetailsFormMemberTypeField',
                                                'class'         =>  'form-control',
                                                'maxlength'     =>  '5',
                                            ),
                        'options'       =>  array
                                            (
                                                'label'         =>  'ZipCode'
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
                                                'id'            =>  'VerificationDetailsFormVcodeField',
                                                'class'         =>  'form-control',
                                            ),
                    )
                );

        // CSRF field
        $csrfValidator      =   new CsrfValidator
                                    (
                                        array
                                        (
                                            'name'          =>  'verification_details_csrf',
                                            'salt'          =>  'saltGoesHere', // todo: create salts in config for each form
                                            'timeout'       =>  600,
                                            'required'      =>  TRUE,
                                            'messages'      =>  array
                                                                (
                                                                    Validator\Csrf::NOT_SAME    =>  'Your Verification cannot be processed at this time. Please go back to your inbox and click the link or contact Tech Support at tech_support@nomsterz.com',
                                                                )
                                        )
                                    );
        $VerificationCsrf         =   new CsrfElement('verification_details_csrf');
        $VerificationCsrf->setAttribute('id', 'VerificationDetailsFormCsrfField');
        $VerificationCsrf->setCsrfValidator($csrfValidator);
        $this->add($VerificationCsrf);

        //assign!
        $this->verification_details_csrf  =   $VerificationCsrf;
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

            // First Name field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'first_name',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Your first name is required and can not be empty.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Your first name is required and can not be empty.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Alpha',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        I18nValidator\Alpha::INVALID        =>  'Please add only letters in your first name.',
                                                                                                                        I18nValidator\Alpha::NOT_ALPHA      =>  'Please add only letters in your first name.',
                                                                                                                        I18nValidator\Alpha::STRING_EMPTY   =>  'Your first name is required and can not be empty.',
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
                                                                                                    'min'       =>  1,
                                                                                                    'max'       =>  60,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\StringLength::INVALID     =>  'Your first name must be more than 1 digit. Valid characters only.',
                                                                                                                        Validator\StringLength::TOO_SHORT   =>  'Your first name is too short.',
                                                                                                                        Validator\StringLength::TOO_LONG    =>  'Your first name is too long.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            // Last Name field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'last_name',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Your last name is required and can not be empty.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Your last name is required and can not be empty.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Alpha',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        I18nValidator\Alpha::INVALID        =>  'Please add only letters in your last name.',
                                                                                                                        I18nValidator\Alpha::NOT_ALPHA      =>  'Please add only letters in your last name.',
                                                                                                                        I18nValidator\Alpha::STRING_EMPTY   =>  'Your last name is required and can not be empty.',
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
                                                                                                    'min'       =>  1,
                                                                                                    'max'       =>  60,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\StringLength::INVALID     =>  'Your last name must be more than 1 digit. Valid characters only.',
                                                                                                                        Validator\StringLength::TOO_SHORT   =>  'Your last name is too short.',
                                                                                                                        Validator\StringLength::TOO_LONG    =>  'Your last name is too long.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            // Gender field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'gender',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Please specify your gender.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Please specify your gender.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Between',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'min'       =>  1,
                                                                                                    'max'       =>  2,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Between::NOT_BETWEEN          =>  'Please specify your gender.',
                                                                                                                        Validator\Between::NOT_BETWEEN_STRICT   =>  'Please specify your gender.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Digits',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Digits::NOT_DIGITS        =>  'Please specify your gender.',
                                                                                                                        Validator\Digits::STRING_EMPTY      =>  'Please specify your gender.',
                                                                                                                        Validator\Digits::INVALID           =>  'Please specify your gender.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            // Member Type field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'member_type',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Please specify your member type.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Please specify your member type.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Between',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'min'       =>  1,
                                                                                                    'max'       =>  6,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Between::NOT_BETWEEN          =>  'Please specify your member type.',
                                                                                                                        Validator\Between::NOT_BETWEEN_STRICT   =>  'Please specify your member type.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Digits',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Digits::NOT_DIGITS        =>  'Please specify your member type.',
                                                                                                                        Validator\Digits::STRING_EMPTY      =>  'Please specify your member type.',
                                                                                                                        Validator\Digits::INVALID           =>  'Please specify your member type.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                        ),
                                                )
                                            )
                            );

            // Zip Code field
            $inputFilter->add
                            (
                                $factory->createInput
                                            (
                                                array
                                                (
                                                    'name'          =>  'zipcode',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'A zip code is required and can not be empty.',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'A zip code is required and can not be empty.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Between',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'min'       =>  0,
                                                                                                    'max'       =>  99999,
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Between::NOT_BETWEEN          =>  'Please specify your zip code.',
                                                                                                                        Validator\Between::NOT_BETWEEN_STRICT   =>  'Please specify your zip code.',
                                                                                                                    )
                                                                                                ),
                                                                                'break_chain_on_failure' => true,
                                                                            ),
                                                                            array
                                                                            (
                                                                                'name'      =>  'Digits',
                                                                                'options'   =>  array
                                                                                                (
                                                                                                    'messages'  =>  array
                                                                                                                    (
                                                                                                                        Validator\Digits::NOT_DIGITS        =>  'Please specify your zip code.',
                                                                                                                        Validator\Digits::STRING_EMPTY      =>  'Please specify your zip code.',
                                                                                                                        Validator\Digits::INVALID           =>  'Please specify your zip code.',
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
                                                                                                                        Validator\NotEmpty::INVALID     =>  'Your Verification details cannot be processed at this time. Please go back to your inbox and click the link or contact Tech Support at tech_support@nomsterz.com',
                                                                                                                        Validator\NotEmpty::IS_EMPTY    =>  'Your Verification details cannot be processed at this time. Please go back to your inbox and click the link or contact Tech Support at tech_support@nomsterz.com',
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
                                                    'name'          =>  'verification_details_csrf',
                                                    'required'      =>  true,
                                                    'validators'    =>  array
                                                                        (
                                                                            $this->verification_details_csrf->getCsrfValidator(),
                                                                        ),
                                                )
                                            )
                            );

            $this->inputFilter  =   $inputFilter;
        }

        return $this->inputFilter;
    }
}
