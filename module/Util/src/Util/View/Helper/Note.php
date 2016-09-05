<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 25/02/2016
 * Time: 11:23
 */

namespace Util\View\Helper;

use Zend\View\Helper\Placeholder;
use Zend\View\Exception;

class Note extends Placeholder\Container\AbstractStandalone
{
	/**
	 * Registry key for placeholder
	 *
	 * @var string
	 */
	protected $regKey = 'Util_View_Helper_Note';

	/**
	 * Is capture lock?
	 *
	 * @var bool
	 */
	protected $captureLock;

	protected $containerFormat = "<div class='note note-%s'>%s</div>";
	protected $titleFormat = '<h4 class="block">%s</h4>';

	/**
	 * Templates for the open/close/separators for message tags
	 *
	 * @var string
	 */
	protected $messageCloseString     = '</li></ul>';
	protected $messageOpenFormat      = '<ul><li>';
	protected $messageSeparatorString = '</li><li>';

	protected $messages = [];
	protected $title;
	protected $type = 'info';

	public function __invoke($title=null, $messages=null, $type='info')
	{
		if($title) {
			$this->title = $title;
		}

		if($messages) {
			if(is_scalar($messages)) {
				$messages = array($messages);
			}

			$this->messages = $messages;
		}

		if($type) {
			$this->type = $type;
		}

		if($this->title && $this->messages) {
			return $this->render();
		}

		return $this;
	}

	public function render()
	{
		$messagesMarkup = sprintf($this->titleFormat, $this->title);
		$messagesMarkup.= $this->messageOpenFormat;
		$messagesMarkup.= implode(
			$this->messageSeparatorString,
			$this->messages
		);
		$messagesMarkup.= $this->messageCloseString;

		return sprintf(
			$this->containerFormat,
			$this->type,
			$messagesMarkup
		);
	}

	/**
	 * Start capture message
	 */
	public function captureMessageStart()
	{
		if ($this->captureLock) {
			throw new Exception\RuntimeException('Cannot nest headScript captures');
		}

		$this->captureLock = true;

		ob_start();
	}

	/**
	 * End capture action and store
	 *
	 * @return void
	 */
	public function captureMessageEnd()
	{
		$content = ob_get_clean();
		$this->captureLock        = false;

		$this->addMessage($content);
	}

	/**
	 * @return string
	 */
	public function getContainerFormat()
	{
		return $this->containerFormat;
	}

	/**
	 * @param string $containerFormat
	 */
	public function setContainerFormat($containerFormat)
	{
		$this->containerFormat = $containerFormat;
	}

	/**
	 * @return string
	 */
	public function getTitleFormat()
	{
		return $this->titleFormat;
	}

	/**
	 * @param string $titleFormat
	 */
	public function setTitleFormat($titleFormat)
	{
		$this->titleFormat = $titleFormat;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessageCloseString()
	{
		return $this->messageCloseString;
	}

	/**
	 * @param string $messageCloseString
	 */
	public function setMessageCloseString($messageCloseString)
	{
		$this->messageCloseString = $messageCloseString;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessageOpenFormat()
	{
		return $this->messageOpenFormat;
	}

	/**
	 * @param string $messageOpenFormat
	 */
	public function setMessageOpenFormat($messageOpenFormat)
	{
		$this->messageOpenFormat = $messageOpenFormat;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessageSeparatorString()
	{
		return $this->messageSeparatorString;
	}

	/**
	 * @param string $messageSeparatorString
	 */
	public function setMessageSeparatorString($messageSeparatorString)
	{
		$this->messageSeparatorString = $messageSeparatorString;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getMessages()
	{
		return $this->messages;
	}

	/**
	 * @param array $messages
	 */
	public function setMessages($messages)
	{
		$this->messages = $messages;
		return $this;
	}

	public function addMessage($message)
	{
		$this->messages[] = $message;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}
}