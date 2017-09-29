<?php
namespace Admin\Form;

use Application\Entity\Post\PostMeta;
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
				'id' => 'text',
                'class' => 'input-lg'
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

		$thumb = new Hidden('meta['.PostMeta::THUMB.']');
		$thumb->setAttributes([
			'id' => 'returnResponsivefilemanager'
		]);
		$this->add($thumb);

		$cover = new Hidden('meta['.PostMeta::COVER.']');
		$cover->setAttributes([
			'id' => 'returnselectimage',
			'class' => 'select-image-input'
		]);
		$this->add($cover);


		$submitButtom = new Button('publish');
		$submitButtom->setValue('Publicar');
		$submitButtom->setAttributes([
			'class' => 'btn blue btn-lg action publish',
			'id' => 'publish'
		]);
		$submitButtom->setOption('fontAwesome', 'fa fa-check');
		$this->add($submitButtom);


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