<?php
namespace Util\Model;

use Zend\Stdlib\ArrayUtils;

class HtmlTable
{
	/**
	 * @var array
	 */
	protected $rows;

	/**
	 * Table heder row
	 *
	 * @var array
	 */
	protected $header;

	/**
	 * Table heder filter
	 *
	 * @var array
	 */
	protected $filter;

	protected $paginator;

	/**
	 * @var arrray
	 */
	protected $attributes;

	public function __construct(array $data = array())
	{
		if(isset($data['header'])) {
			$this->setHeader($data['header']);
		}

		if(isset($data['filter'])) {
			$this->setFilter($data['filter']);
		}

		if(isset($data['rows'])) {
			$this->setRows($data['rows']);
		}

		if(isset($data['attributes'])) {
			$this->setAttributes($data['attributes']);
		}
	}

	/**
	 * @param array $rows
	 * @return Table
	 */
	public function setRows(array $rows)
	{
		$this->rows = $rows;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRows()
	{
		return $this->rows;
	}

	/**
	 * @param array $header
	 * @return Table
	 */
	public function setHeader(array $header)
	{
		$this->header = $header;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getHeader()
	{
		return $this->header;
	}

	public function getFilter()
	{
		return $this->filter;
	}

	public function setFilter($filter)
	{
		$this->filter = $filter;
		return $this;
	}

	/**
	 * @param array $attributes An associative array of tag attributes. Each key-value pair is an attribute name and value.
	 * @return Table
	 */
	public function setAttributes(array $attributes)
	{
		$this->attributes = $attributes;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @return bool
	 */
	public function hasAttributes()
	{
		return isset($this->attributes);
	}

	public function getAttribute($key)
	{
		if($this->hasAttributes() && isset($this->attributes[$key])) {
			return true;
		}

		return false;
	}

	public function addAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	public function hasHeader()
	{
		if(empty($this->header) && empty($this->filter)) {
			return false;
		}

		return true;
	}

	public function getPaginator()
	{
		return $this->paginator;
	}

	public function setPaginator($paginator)
	{
		$this->paginator = $paginator;
		return $this;
	}
}