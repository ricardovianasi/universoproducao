<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/09/2016
 * Time: 14:28
 */

namespace Application\View\Helper;


use Zend\View\Helper\AbstractHelper;

class Truncate extends AbstractHelper
{
    public function __invoke($str, $length = 100, $exact=false, $ending='...', $stripTags=true)
    {
        if($stripTags) {
            $str = strip_tags($str);
        }

        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen($str) <= $length) {
            return $str;
        } else {
            $truncate = mb_substr($str, 0, $length - strlen($ending));
        }

        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurrence of a space...
            $spacepos = strrpos($truncate, ' ');
            if ($spacepos > 0) {
                // ...and cut the text in this position
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }

        // add the defined ending to the text
        $truncate .= $ending;
        return $truncate;
    }
}