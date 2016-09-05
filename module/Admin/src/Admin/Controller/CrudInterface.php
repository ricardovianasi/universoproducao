<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2016
 * Time: 09:27
 */

namespace Admin\Controller;


interface CrudInterface
{
	public function indexAction();

	public function createAction($data);

	public function updateAction($id, $data);

	public function deleteAction($id);

	public function persist($data, $id=null);
}