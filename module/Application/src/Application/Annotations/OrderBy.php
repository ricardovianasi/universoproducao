<?php

/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 21/12/2015
 * Time: 08:01
 */

namespace Application\Annotations;

/**
 * @Annotation
 * @Target("CLASS")
 */
class OrderBy
{
	/**
	 * @var array<string>
	 */
	public $value;
}