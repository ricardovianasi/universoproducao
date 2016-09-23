<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Thumborize extends AbstractHelper
{
    public function __invoke($content)
    {
        if(empty($content))
            return $content;

        $domDocument = new \DOMDocument();
        $domDocument->loadHTML($content);
        $images = $domDocument->getElementsByTagName('img');
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            $width = $img->getAttribute('width');
            $height = $img->getAttribute('height');

            $thumborize  = $this->generateThumborUrl($src, $width, $height);
            $content = str_replace($src, $thumborize, $content);
        }

        return $content;
    }

    public function generateThumborUrl($url, $width=null, $height=null)
    {
        $thumborHelper = $this->getView()->plugin('thumbor');
        $url = $thumborHelper()->url($url);

        if($width && $height) {
            $url->resize($width, $height);
        }

        return (string) $url;
    }
}