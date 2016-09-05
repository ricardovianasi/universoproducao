<?php
namespace TwbBundle\Form\Element;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Zend\Form\Element\Text;
use Zend\Form\ElementInterface;

/**
 * Date Picker form element
 *
 * @author Ricardo Viana
 *
 */
class TagsInput extends Text
{
	public function setValue($value)
	{
		if(empty($value)) {
			return;
		}

		if ($value instanceof ArrayCollection || $value instanceof PersistentCollection) {
			$coll = $value->toArray();
			$value = [];
			foreach($coll as $c) {
				$value[$c->getName()];
			}
		} else {
			$value = (array) $value;
		}

		$value = implode(',', $value);
		parent::setValue($value);
	}
}