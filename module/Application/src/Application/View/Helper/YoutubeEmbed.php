<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class YoutubeEmbed extends AbstractHelper
{
	public function __invoke($url, array $options=[])
	{
		$url = sprintf('https://www.youtube.com/embed/%s', $this->getIdFromUrl($url));
		if(!empty($options)) {
		    $url.='?' . http_build_query($options);
        }

        return $url;
	}

	public function getIdFromUrl($url)
	{
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
		return $matches[0];
	}
}