<?php
namespace Admin\Form;

use Application\Entity\Post\PostStatus;
use Application\Entity\Site\Site;
use TwbBundle\Form\Element\TagsInput;
use TwbBundle\Form\Element\Tinymce;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\Form\Element\Text;

class PostForm extends Form
{
	use EntityManagerTrait;

	public function __construct($em=null)
	{
		if($em) {
			$this->entityManager = $em;
		}

		parent::__construct('post-form');
		$this->setAttribute('method', 'POST');

		$title = new Text('title');
		$title->setLabel('Título')
			->setAttributes([
				'id' => 'text'
			]);
		$this->add($title);

		$subTitle = new Text('subtitle');
		$subTitle->setLabel('Subtítulo');
		$this->add($subTitle);

		$slugBtn = new Button('slug-edit');
		$slugBtn->setValue('');
		$slugBtn->setOption('fontAwesome', 'fa fa-pencil-square-o');
		$slugBtn->setAttribute('class', 'btn blue slug-action-edit');

		$slug = new Text('slug');
		$slug->setLabel('Slug')
			->setOption('add-on-append', $slugBtn)
			->setAttributes([
				'placeholder' => 'Informe a url amigável',
				'readonly' => 'readonly',
				'id' => 'slug'
			]);
		$this->add($slug);

		$tags = new TagsInput('tags');
		$tags->setLabel('Tags')
			->setAttribute('placeholder', 'Tags');
		$this->add($tags);

		$postDate = new Hidden('post_date');
		$this->add($postDate);

		$status = new Select('status');
		$status->setValueOptions(PostStatus::toArray());
		$status->setValue(PostStatus::DRAFT);
		$this->add($status);

		$content = new Tinymce('content');
		$content->setAttribute('id', 'tinymce');
		$this->add($content);

		$thumb = new Hidden('thumb');
		$thumb->setAttributes([
			'id' => 'returnResponsivefilemanager'
		]);
		$this->add($thumb);

		$cover = new Hidden('cover');
		$cover->setAttributes([
			'id' => 'returnselectimage',
			'class' => 'select-image-input'
		]);
		$this->add($cover);

		$coverCaption = new Text('cover_caption');
		$coverCaption->setAttributes([
			'class' => 'select-image-input-caption',
			'placeholder' => 'Legenda'
		]);
		$this->add($coverCaption);

		$submitButtom = new Button('publish');
		$submitButtom->setValue('Publicar');
		$submitButtom->setAttributes([
			'class' => 'btn blue btn-lg action publish',
			'id' => 'publish'
		]);
		$submitButtom->setOption('fontAwesome', 'fa fa-check');
		$this->add($submitButtom);

		$this->add([
			'type' => 'select',
			'name' => 'sites-enabled',
			'options' => [
				'label' => 'Selecione onde a notícia vai aparecer',
				'value_options' => $this->populateSite(),
				'empty_option' => 'Selecione',
			],
			'attributes' => [
				'type'=>'select',
				'class' => 'select2',
			]
		]);

		$this->add([
			'name' => 'publish_all_sites',
			'type' => 'checkbox',
			'options' => [
				'label' => 'Exibir em todos os sites',
				'checked_value' => 1,
				'unchecked_value' => 0,
			],
			'attributes' => [
				'class' => 'icheck'
			]
		]);

		$this->add([
			'name' => 'publish_highlight_all_sites',
			'type' => 'checkbox',
			'options' => [
				'label' => 'Destacar em todos os sites',
				'checked_value' => 1,
				'unchecked_value' => 0
			],
			'attributes' => [
				'class' => 'icheck',
				'disabled' => 'disabled'
			]
		]);

		$this->getInputFilter()->add([
			'name' => 'publish_highlight_all_sites',
			'required' => false
		]);

		$this->getInputFilter()->add([
			'name' => 'publish_all_sites',
			'required' => false
		]);

		$this->getInputFilter()->add([
			'name' => 'sites-enabled',
			'required' => false
		]);
	}

	public function populateSite()
	{
		$options = [];

		$sites = $this->getEntityManager()->getRepository(Site::class)->findEnabledSites();
		foreach($sites as $site) {
			$options[$site->getId()] = $site->getName();
		}

		return $options;
	}

	public function setData($data)
	{
		unset($data['sites-enabled']);

		$publish_all_sites = empty($data['publish_all_sites'])
			? 0
			: (int) $data['publish_all_sites'];

		$data['publish_all_sites'] = $publish_all_sites;
		if($publish_all_sites) {
			$this->get('publish_highlight_all_sites')->setAttribute('disabled', '');
		}

		$data['publish_highlight_all_sites'] = empty($data['publish_highlight_all_sites'])
			? 0
			: (int) $data['publish_highlight_all_sites'];


		parent::setData($data);
	}
}