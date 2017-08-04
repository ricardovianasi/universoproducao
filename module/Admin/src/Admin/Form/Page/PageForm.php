<?php
namespace Admin\Form\Page;

use Admin\Form\PostForm;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Site\SiteMeta;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class PageForm extends PostForm
{
	public function __construct($em)
	{
		parent::__construct($em);

		$this->add([
			'name' => 'parent',
			'type' => 'Select',
			'options' => [
				'label' => 'Página Mãe',
				'empty_option' => '(sem mãe)'
			]
		]);

		$this->add([
			'name' => 'meta['.SiteMeta::CUSTOM_POST_ACTION.']',
			'type' => 'Select',
			'options' => [
				'label' => 'Template',
				'empty_option' => '(template padrão)'
			]
		]);

//        $inputFilter = new InputFilter();
//
//        $metaTarget = new Input('meta['.PostMeta::TARGET_BLANK.']');
//        $metaTarget->setRequired(false);
//        $inputFilter->add($metaTarget);
//
//        $this->setInputFilter($inputFilter);

		$this->getInputFilter()
			->add([
				'name' => 'parent',
				'required'   => false
			])
			->add([
				'name' => 'meta['.SiteMeta::CUSTOM_POST_ACTION.']',
				'required'   => false
			]);
	}

	public function populateParentPages($siteId, $escapePageId=null)
	{
		$qb = $this->getEntityManager()->getRepository(Post::class)->createQueryBuilder('p');
		$qb->andWhere('p.site = :siteId')
			->andWhere('p.status = :status')
			->andWhere('p.type = :type')
			->setParameters([
				'siteId' => $siteId,
				'status' => PostStatus::PUBLISHED,
				'type' => PostType::PAGE
			]);

		if($escapePageId) {
			$qb->andWhere('p.id != :id')
				->setParameter('id', $escapePageId)
				->andWhere('(p.parent != :id OR  p.parent is NULL) ');
		}
		$search = $qb->getQuery()->getResult();

		$pages = [];
		foreach($search as $p) {
			$pages[$p->getId()] = $p->getTitle()." [".strtoupper($p->getLanguage()->getId())."]";
		}

		$this->get('parent')->setValueOptions($pages);
	}

	public function populateMetaTemplate($siteId)
	{
		$metaValues = $this
			->getEntityManager()
			->getRepository(SiteMeta::class)
			->createQueryBuilder('p')
			->andWhere('p.site = :siteId')
			->andWhere('p.key = :metakey')
			->setParameters([
				'siteId' => $siteId,
				'metakey' => SiteMeta::CUSTOM_POST_ACTION
			])
			->getQuery()
			->getResult();

		$meta = [];
		foreach ($metaValues as $mVal) {
			$meta[$mVal->getValue()] = $mVal->getAlias();
		}

		$this->get('meta['.SiteMeta::CUSTOM_POST_ACTION.']')->setValueOptions($meta);
	}

	public function setData($data)
	{
		if(!empty($data['parent'])) {
			if(is_object($data['parent'])) {
				$parent = $data['parent'];
			} elseif(is_scalar($data['parent'])) {
				$parent = $this
					->getEntityManager()
					->getRepository(Post::class)
					->find($data['parent']);
			}

			$data['parent'] = $parent->getId();
		}

		return parent::setData($data);
	}
}