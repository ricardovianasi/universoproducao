<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Gender extends AbstractHelper
{
    public function __invoke($op)
    {
        $s = '';
        switch ($op) {
            case 'm':
            case 'M':
            case 1:
                $s = "Masculino";
                break;
            case 'f':
            case 'F':
            case 2:
                $s = "Feminino";
                break;
        }

        return $s;
    }
}