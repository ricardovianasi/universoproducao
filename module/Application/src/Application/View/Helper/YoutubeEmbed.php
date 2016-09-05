<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class YoutubeEmbed extends AbstractHelper
{
	public function __invoke($url)
	{
		return sprintf('https://www.youtube.com/embed/%s', $this->getIdFromUrl($url));
	}

	public function getIdFromUrl($url)
	{
		/*$parts = parse_url($url);
		if(isset($parts['query'])){
			parse_str($parts['query'], $qs);
			if(isset($qs['v'])){
				return $qs['v'];
			}else if(isset($qs['vi'])){
				return $qs['vi'];
			}
		}
		if(isset($parts['path'])){
			$path = explode('/', trim($parts['path'], '/'));
			return $path[count($path)-1];
		}
		return false;*/

		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);

		return $matches[0];
	}
}