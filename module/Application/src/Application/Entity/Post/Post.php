<?php
namespace Application\Entity\Post;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Annotations as APP;

/**
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Application\Repository\Post\Post")
 * @APP\OrderBy({"updatedAt"="DESC"})
 */
class Post extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\User\User")
	 * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
	 */
	private $author;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Site\Site")
	 * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
	 */
	private $site;

	/**
	 * @ORM\ManyToOne(targetEntity="Post", inversedBy="children")
	 * @ORM\JoinColumn(name="post_parent_id", referencedColumnName="id")
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Post", mappedBy="parent",  fetch="EXTRA_LAZY")
	 */
	private $children;

	/** @ORM\Column(name="content", type="string", nullable=true) */
	private $content;

	/** @ORM\Column(name="title", type="string", nullable=true) */
	private $title;

	/** @ORM\Column(name="subtitle", type="string", nullable=true) */
	private $subtitle;

	/** @ORM\Column(name="slug", type="string", nullable=false) */
	private $slug;

	/** @ORM\Column(name="type", type="string", nullable=false) */
	private $type;

	/** @ORM\Column(name="status", type="string", nullable=false) */
	private $status = PostStatus::DRAFT;

	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Tag", cascade={"all"})
	 * @ORM\JoinTable(name="tag_has_post",
	 *   joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
	 *   inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
	 * )
	 */
	private $tags;

	/** @ORM\Column(name="`order`", type="integer", nullable=false) */
	private $order;

	/** @ORM\Column(name="created_at", type="datetime", nullable=true) */
	private $createdAt;

	/** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
	private $updatedAt;

	/** @ORM\Column(name="post_date", type="datetime", nullable=true) */
	private $postDate;

	public $breadcrumbs;

	/** @ORM\OneToMany(targetEntity="PostMeta", mappedBy="post", cascade={"ALL"}) */
	public $meta;

	public function __construct()
	{
		$this->tags = new ArrayCollection();
		$this->children = new ArrayCollection();
		$this->meta = new ArrayCollection();
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

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return \Application\Entity\User\User
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
	}

	/**
	 * @return Post
	 */
	public function getParent()
	{
		return $this->parent;
	}

	public function setParent($parent)
	{
		$this->parent = $parent;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getSubtitle()
	{
		return $this->subtitle;
	}

	public function setSubtitle($subTitle)
	{
		$this->subtitle = $subTitle;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getStatus()
	{
		if($this->status == PostStatus::PUBLISHED) {
			$now = new \DateTime();
			if($this->getPostDate() > $now) {
				return PostStatus::SCHEDULED;
			}
		}

		return $this->status;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function setCreatedAt($createdAt)
	{
		$this->parseData($createdAt, $this->createdAt);
	}

	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt($updatedAt)
	{
		$this->parseData($updatedAt, $this->updatedAt);
	}

	public function getPostDate()
	{
		return $this->postDate;
	}

	public function setPostDate($postdate)
	{
		$this->parseData($postdate, $this->postDate, true);
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function setTags($tags)
    {
        $this->tags = $tags;
    }

	public function addTag($tag)
	{
		if(!$this->getTags()->contains($tag)) {
			$this->getTags()->add($tag);
		}
		return $this;
	}

	public function removeTag($tag)
	{
		if(!$this->getTags()->contains($tag)) {
			$this->getTags()->removeElement($tag);
		}
		return $this;
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

	public function hasChildren()
	{
		if($this->children->count() > 0) return true;
		else return false;
	}
	
	/**
	 * @return mixed
	 */
	public function getBreadcrumbs()
	{
		//return $this->breadcrumbs;
		$breadcrumbs[] = [
			(string) $this->slug => $this->title
		];

		$parent = $this->getParent();
		while($parent) {
			array_unshift($breadcrumbs, [(string)$parent->getSlug() => $parent->getTitle()]);
			$parent = $parent->getParent();
		}

		return $this->breadcrumbs = $breadcrumbs;
	}

	/**
	 * @param mixed $breadcrumbs
	 */
	public function setBreadcrumbs($breadcrumbs)
	{
		$this->breadcrumbs = $breadcrumbs;
	}

	/**
	 * @return mixed
	 */
	public function getRelativeUrl()
	{
		$str = "";
		array_map(function($item) use(&$str) {
			$str.= key($item) . '/';
		}, $this->getBreadcrumbs());

		return rtrim($str, '/');
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
	public function getMeta($metaKey=null)
	{
	    if(!$metaKey)
		    return $this->meta;

        foreach ($this->meta as $meta) {
            if($meta->getKey() == $metaKey) {
                return $meta->getValue();
            }
        }

        return null;
	}

	/**
	 * @param mixed $meta
	 */
	public function setMeta($meta)
	{
		$this->meta = $meta;
	}

	public function addMeta(PostMeta $meta)
	{
		$meta->setPost($this);

		if(!$this->meta->contains($meta)) {
			$this->meta->add($meta);
		}

		return $this;
	}

	public function hasMeta($metaKey)
    {
	    foreach ($this->getMeta() as $meta) {
	        if($meta->getKey() == $metaKey) {
	            return true;
            }
        }

        return false;
    }
}