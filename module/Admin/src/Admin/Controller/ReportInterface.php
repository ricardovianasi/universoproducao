<?php
namespace Admin\Controller;


interface ReportInterface
{
	public function reportPrepare($items, $format='json');
}