<?php
namespace Application\Entity\Site;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="site_meta")
 * @ORM\Entity
 */
class SiteMeta extends AbstractEntity
{
	const CUSTOM_POST_TEMPLATE = 'custom_post_template';
    const CUSTOM_POST_ACTION = 'custom_post_action';

	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(name="`key`", type="string", nullable=false) */
	private $key;

	/** @ORM\Column(name="`value`", type="string", nullable=false) */
	private $value;

	/** @ORM\Column(type="string", nullable=false) */
	private $alias;

	/**
	 * @ORM\ManyToOne(targetEntity="Site", inversedBy="meta")
	 * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
	 */
	private $site;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @param mixed $key
	 */
	public function setKey($key)
	{
		$this->key = $key;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @param mixed $site
	 */
	public function setSite($site)
	{
		$this->site = $site;
	}

	/**
	 * @return mixed
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @param mixed $alias
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
	}
}