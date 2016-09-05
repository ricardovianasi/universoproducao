<?php

return array(
    'twbbundle' => array (
        'ignoredViewHelpers' => array (
            'file',
            'checkbox',
            'radio',
            'submit',
            'multi_checkbox',
            'static',
            'button',
            'reset'
        ),
        'type_map' => array(
            'date_picker'		=> 'formDatePicker',
            'ckeditor'			=> 'formCKEditor',
            'tagsinput'			=> 'formTagsInput',
            'date_time_picker'	=> 'formDateTimePicker',
            'tinymce'			=> 'formTinymce',
            'cpf'				=> 'formCpf',
            'cep'				=> 'formCep'
        ),
        'class_map' => array(
            'TwbBundle\Form\Element\DatePicker' 	=> 'formDatePicker',
            'TwbBundle\Form\Element\CKEditor'  		=> 'formCKEditor',
            'TwbBundle\Form\Element\Tinymce'  		=> 'formTinymce',
            'TwbBundle\Form\Element\TagsInput'		=> 'formTagsInput',
            'TwbBundle\Form\Element\DateTimePicker'	=> 'formDateTimePicker',
            'TwbBundle\Form\Element\Cpf'			=> 'formCpf',
            'TwbBundle\Form\Element\Cep'			=> 'formCep'
        ),
    ),
    'service_manager' => array (
        'factories' => array (
            'TwbBundle\Options\ModuleOptions' => 'TwbBundle\Options\Factory\ModuleOptionsFactory'
        )
    ),
    'view_helpers' => array (
        'invokables' => array (
            //Alert
            'alert'                 => 'TwbBundle\View\Helper\TwbBundleAlert',
            //Badge
            'badge'                 => 'TwbBundle\View\Helper\TwbBundleBadge',
            //Button group
            'buttonGroup'           => 'TwbBundle\View\Helper\TwbBundleButtonGroup',
            //DropDown
            'dropDown'              => 'TwbBundle\View\Helper\TwbBundleDropDown',
            //Form
            'form'                  => 'TwbBundle\Form\View\Helper\TwbBundleForm',
            'formButton'            => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formSubmit'            => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formCheckbox'          => 'TwbBundle\Form\View\Helper\TwbBundleFormCheckbox',
            'formCollection'        => 'TwbBundle\Form\View\Helper\TwbBundleFormCollection',
            'formElementErrors'     => 'TwbBundle\Form\View\Helper\TwbBundleFormElementErrors',
            'formMultiCheckbox'     => 'TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox',
            'formRadio'             => 'TwbBundle\Form\View\Helper\TwbBundleFormRadio',
            'formRow'               => 'TwbBundle\Form\View\Helper\TwbBundleFormRow',
            'formStatic'            => 'TwbBundle\Form\View\Helper\TwbBundleFormStatic',
            'formDatePicker'	    => 'TwbBundle\Form\View\Helper\TwbBundleFormDatePicker',
            'formDateTimePicker'    => 'TwbBundle\Form\View\Helper\TwbBundleFormDateTimePicker',
            'formCKEditor'			=> 'TwbBundle\Form\View\Helper\TwbBundleFormCKEditor',
            'formTinymce'			=> 'TwbBundle\Form\View\Helper\TwbBundleFormTinymce',
            'formTagsInput'			=> 'TwbBundle\Form\View\Helper\TwbBundleFormTagsInput',
            'formCep'				=> 'TwbBundle\Form\View\Helper\TwbBundleFormCep',
			
            //Form Errors
            'formErrors' => 'TwbBundle\Form\View\Helper\TwbBundleFormErrors',
            //Glyphicon
            'glyphicon' => 'TwbBundle\View\Helper\TwbBundleGlyphicon',
            //FontAwesome
            'fontAwesome' => 'TwbBundle\View\Helper\TwbBundleFontAwesome',
            //Label
            'label' => 'TwbBundle\View\Helper\TwbBundleLabel'
        ),
        'factories' => array (
            'formElement'   => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'formCpf'       => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormCpfFactory'
        )
    ),
);
