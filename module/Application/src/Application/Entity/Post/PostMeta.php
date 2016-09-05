<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 18/03/2016
 * Time: 11:17
 */

namespace Application\Entity\Post;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="post_meta")
 * @ORM\Entity
 */
class PostMeta extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Post", inversedBy="meta")
	 * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
	 */
	private $post;

	/** @ORM\Column(name="`key`", type="string", nullable=false) */
	private $key;

	/** @ORM\Column(name="`value`", type="text", nullable=false) */
	private $value;

	/** @ORM\Column(type="string", nullable=false) */
	private $alias;

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
	public function getPost()
	{
		return $this->post;
	}

	/**
	 * @param mixed $post
	 */
	public function setPost($post)
	{
		$this->post = $post;
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