<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/03/2016
 * Time: 12:51
 */

namespace Application\Entity\Site\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu_items")
 * @ORM\Entity(repositoryClass="Application\Repository\Site\Menu\Item")
 */
class Item extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Menu", inversedBy="items")
	 * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
	 */
	private $menu;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Post\Post")
	 * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
	 */
	private $post;

	/**
	 * @ORM\ManyToOne(targetEntity="Item", inversedBy="children", cascade={"persist"}, fetch="EAGER")
	 * @ORM\JoinColumn(name="menu_item_parent_id", referencedColumnName="id", nullable=true)
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="parent", cascade={"persist"}, fetch="EAGER")
	 * @ORM\OrderBy({"order" = "ASC"})
	 **/
	private $children;

	/**
	 * @ORM\Column(type="string")
	 */
	private $label;

	/**
	 * @ORM\Column(type="integer", name="`order`")
	 */
	private $order;

	/**
	 * @ORM\Column(name="external_url", type="string")
	 */
	private $externalUrl;

	/**
	 * @ORM\Column(name="target_blank", type="boolean")
	 */
	private $targetBlank = false;

	public function __construct()
	{
		$this->children = new ArrayCollection();
	}

	/**
	 * @return array
	 */
	public function getDefaultInputFilter()
	{
		return $this->defaultInputFilter;
	}

	/**
	 * @param array $defaultInputFilter
	 */
	public function setDefaultInputFilter($defaultInputFilter)
	{
		$this->defaultInputFilter = $defaultInputFilter;
	}

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
	public function getMenu()
	{
		return $this->menu;
	}

	/**
	 * @param mixed $menu
	 */
	public function setMenu($menu)
	{
		$this->menu = $menu;
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
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param mixed $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}

	/**
	 * @return mixed
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * @param mixed $children
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}

	/**
	 * @return mixed
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param mixed $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @param mixed $order
	 */
	public function setOrder($order)
	{
		$this->order = $order;
	}

	/**
	 * @return mixed
	 */
	public function getExternalUrl()
	{
		return $this->externalUrl;
	}

	/**
	 * @param mixed $externalUrl
	 */
	public function setExternalUrl($externalUrl)
	{
		$this->externalUrl = $externalUrl;
	}

	/**
	 * @return mixed
	 */
	public function getTargetBlank()
	{
		return $this->targetBlank;
	}

	/**
	 * @param mixed $targetBlank
	 */
	public function setTargetBlank($targetBlank)
	{
		$this->targetBlank = $targetBlank;
	}

	public function isExternalLink()
	{
		if($this->post) {
			return false;
		}

		return true;
	}

	public function hasChildren()
	{
		if($this->children && $this->children->count() > 0)
			return true;

		return false;
	}


}