<?php
namespace Admin\Form\Page;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;
use Application\Entity\Post\PostStatus;

class PageSearchForm extends Form
{
	public function __construct()
	{
		parent::__construct('page-search');

		$this->setAttributes([
			'id' => 'page-search',
			'method' => 'GET'
		]);

		$title = new Text('title');
		$title->setAttribute('class', 'input-sm');
		$this->add($title);

		$dateInit = new Text('dateInit');
		$dateInit->setAttributes([
			'placeholder' => 'De',
			'class' => 'form-control form-filter input-sm'
		]);
		$this->add($dateInit);

		$dateEnd = new Text('dateEnd');
		$dateEnd->setAttributes([
			'placeholder' => 'Até',
			'class' => 'form-control form-filter input-sm'
		]);
		$this->add($dateEnd);

		$status = new Select('status');
		$status->setEmptyOption('Todos');
		$status->setAttribute('class', 'input-sm');
		$status->setValueOptions(PostStatus::toArray());
		$this->add($status);

		$author = new Text('author');
		$author->setAttribute('class', 'input-sm');
		$this->add($author);
	}
}