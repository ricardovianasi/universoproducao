<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/03/2016
 * Time: 12:06
 */

namespace Admin\Form;


use Application\Entity\Post\PostMeta;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class BannerForm extends Form
{
	public function __construct()
	{
		parent::__construct();

        $this->add([
            'name' => 'meta['.PostMeta::IMAGE.']',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'file',
                'class' => 'select-image-input'
            ],
        ]);

		$this->add([
			'name' => 'title',
			'attributes' => [
				'placeholder' => 'Informe um título'
			],
            'options' => [
                'label' => 'Título'
            ]
		]);

        $this->add([
            'type' => 'Number',
            'name' => 'order',
            'attributes' => [
            ],
            'options' => [
                'label' => 'Posição'
            ]
        ]);

        $this->add([
            'name' => 'content',
            'type' => 'textarea',
            'attributes' => [
                'rows' => 3,
                'placeholder' => 'Descrição'
            ],
            'options' => [
                'label' => 'Descrição'
            ]
        ]);

		$this->add([
			'name' => 'meta['.PostMeta::LINK.']',
			'attributes' => [
				'placeholder' => 'http://'
			],
            'options' => [
                'label' => 'Link'
            ]
		]);

		$this->add([
			'name' => 'subtitle',
            'options' => [
                'label' => 'Créditos'
            ]
		]);

		$this->add([
			'name' => 'meta['.PostMeta::TARGET_BLANK.']',
			'type' => 'checkbox',
			'options' => [
				'checked_value' => 1,
				'unchecked_value' => 0,
			],
			'attributes' => [
				'class' => 'icheck'
			]
		]);

        $inputFilter = new InputFilter();

		$metaTarget = new Input('meta['.PostMeta::TARGET_BLANK.']');
		$metaTarget->setRequired(false);
		$inputFilter->add($metaTarget);

		$this->setInputFilter($inputFilter);
	}

	public function setData($data)
    {
        if(!empty($data['meta'])) {
            foreach ($data['meta'] as $key=>$meta) {

                if(is_object($meta)) {
                    $data['meta['.$meta->getKey().']'] = $meta->getValue();
                } else {
                    $data['meta['.$key.']'] = $meta;
                }
            }
        }

        parent::setData($data);
    }
}