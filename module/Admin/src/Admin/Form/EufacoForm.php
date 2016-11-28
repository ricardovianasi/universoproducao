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

class EufacoForm extends Form
{
	public function __construct()
	{
		parent::__construct();

		$this->add([
			'name' => 'description',
			'attributes' => [
				'placeholder' => 'Informe uma descrição'
			],
            'options' => [
                'label' => 'Descrição'
            ]
		]);

        $this->add([
            'name' => 'thumb',
            'type' => 'hidden',
            'attributes' => [
                'placeholder' => 'Informe uma descrição',
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Imagem'
            ]
        ]);

        $this->add([
            'name' => 'video',
            'attributes' => [
                'placeholder' => 'Informe uma url do Youtube'
            ],
            'options' => [
                'label' => 'Url'
            ]
        ]);
	}
}