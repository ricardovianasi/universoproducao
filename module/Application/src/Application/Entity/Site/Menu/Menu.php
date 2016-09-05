<?php
namespace Application\Entity\Site\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu")
 * @ORM\Entity
 */
class Menu extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Site\Site")
	 * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
	 */
	private $site;

	/**
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="menu", cascade={"persist"}, fetch="EAGER")
	 */
	private $items;

	public function __construct()
	{
		$this->items = new ArrayCollection();
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
	 * @return ArrayCollection
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @param mixed $items
	 */
	public function setItems($items)
	{
		$this->items = $items;
	}

	public function addItem(Item $item)
	{
		if(!$this->getItems()->contains($item)) {
			$item->setMenu($this);
			$this->getItems()->add($item);
		}

		return $this;
	}
}