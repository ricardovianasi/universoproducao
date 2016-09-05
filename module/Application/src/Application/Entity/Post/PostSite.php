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
 * @ORM\Table(name="post_has_sites")
 * @ORM\Entity
 */
class PostSite extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Post", inversedBy="sites")
	 * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
	 */
	private $post;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Site\Site")
	 * @ORM\JoinColumn(name="site_version_id", referencedColumnName="id")
	 */
	private $site;

	/** @ORM\Column(name="highlight", type="boolean", nullable=true) */
	private $highlight = false;

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
	public function getHighlight()
	{
		return $this->highlight;
	}

	/**
	 * @param mixed $highlight
	 */
	public function setHighlight($highlight)
	{
		$this->highlight = (boolean) $highlight;
	}

	public function isHighlight() {
		return (boolean) $this->getHighlight();
	}
}