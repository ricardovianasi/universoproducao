<?php
namespace Util\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class Messages extends AbstractHelper
{
	const
		SUCCESS = 'success',
		INFO 	= 'info',
		WARNING = 'warning',
		DANGER 	= 'danger';

	/**
	 * Array of messages
	 *
	 * array(
	 *     'type' => 'type of message',
	 *     'title' => 'title of message',
	 *     'message' => 'message description'
	 * )
	 *
	 * @var array
	 */
	protected $messages;


	/**
	 * @var string
	 */
	protected $titleFormat = '<h4 class="block">%s </h4>';

	/**
	 * @var string
	 */
	protected $alertFormat = '<div %s>%s</div>';

	/**
	 * @var string
	 */
	protected $dismissButtonFormat = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';


	public function __construct($messages=null)
	{
		$this->messages = $messages;
	}

	/**
	 * @param array $messages
	 * @param null $alertAttributes
	 * @param bool $dismissable
	 *
	 * @return $this|string
	 */
	public function __invoke($messages=[], $alertAttributes = null, $dismissable = false)
	{
		if($messages === null) {
			return $this;
		} elseif(empty($messages) && $this->hasMessages()) {
			return $this->render($this->messages, $alertAttributes, $dismissable);
		} elseif(!empty($messages)) {
			return $this->render($messages, $alertAttributes, $dismissable);
		} else {
			return '';
		}
	}

	public function success($message, $title=null, $alertAttributes = null, $dismissable = false)
	{
		if(!is_scalar($message)) {
			throw new zInvalidArgumentException('Alert message expects a scalar value, "'
				. gettype($message) . '" given');
		}

		return $this->render([[
			'type'=>self::SUCCESS,
			'message' => $message,
			'title' => $title
		]], $alertAttributes, $dismissable);
	}

	public function info($message, $title=null, $alertAttributes = null, $dismissable = false)
	{
		if(!is_scalar($message)) {
			throw new zInvalidArgumentException('Alert message expects a scalar value, "'
				. gettype($message) . '" given');
		}

		return $this->render([[
			'type'=>self::INFO,
			'message' => $message,
			'title' => $title
		]], $alertAttributes, $dismissable);

	}

	public function danger($message, $title=null, $alertAttributes = null, $dismissable = false)
	{
		if(!is_scalar($message)) {
			throw new zInvalidArgumentException('Alert message expects a scalar value, "'
				. gettype($message) . '" given');
		}

		return $this->render([[
			'type'=>self::DANGER,
			'message' => $message,
			'title' => $title
		]], $alertAttributes, $dismissable);

	}

	public function warning($message, $title=null, $alertAttributes = null, $dismissable = false)
	{
		if (!is_scalar($message)) {
			throw new zInvalidArgumentException('Alert message expects a scalar value, "'
				. gettype($message) . '" given');
		}

		return $this->render([[
			'type' => self::WARNING,
			'message' => $message,
			'title' => $title
		]], $alertAttributes, $dismissable);
	}

	/**
	 * @param $alertMessages
	 * @param null $alertAttributes
	 * @param bool $dismissable
	 * @return string
	 */
	public function render($alertMessages, $alertAttributes = null, $dismissable = false)
	{
		if(!is_array($alertMessages)) {
			throw new \InvalidArgumentException('Alert expects a array value, "'
				. gettype($alertMessages) . '" given');
		}

		if (empty($alertAttributes)) {
			$alertAttributes = array('class' => 'note');
		} elseif (is_string($alertAttributes)) {
			$alertAttributes = array('class' => $alertAttributes);
		} elseif (!is_array($alertAttributes)) {
			throw new \InvalidArgumentException('Alert attributes expects a string or an array, "'
				. gettype($alertAttributes) . '" given');
		} elseif (empty($alertAttributes['class'])) {
			throw new \InvalidArgumentException('Alert "class" attribute is empty');
		} elseif (!is_string($alertAttributes['class'])) {
			throw new \InvalidArgumentException('Alert "class" attribute expects string, "'
				. gettype($alertAttributes) . '" given');
		}

		$renderHtml = '';

		foreach($alertMessages as $message) {
			if(!is_array($message)) {
				throw new \InvalidArgumentException('A alert item attribute expects an array, "'
					. gettype($message) . '" given');
			}

			if(empty($message['type'])) {
				$message['type'] = self::SUCCESS;
			}

			$defaultAttr = $alertAttributes;

			if (!preg_match('/(\s|^)note(\s|$)/', $defaultAttr['class'])) {
				$defaultAttr['class'] .= ' note';
			}

			if (!preg_match('/(\s|^)note-'.$message['type'].'(\s|$)/',$defaultAttr['class'])) {
				$defaultAttr['class'] .= ' note-'.$message['type'];
			}

			if($dismissable) {
				$message['message'] = $message['message'] . $this->dismissButtonFormat;
				if (!preg_match('/(\s|^)alert-dismissable(\s|$)/', $defaultAttr['class'])) {
					$defaultAttr['class'] .= ' alert-dismissable';
				}
			}

			if(!empty($message['title'])) {
				$message['message'] =
					(sprintf($this->titleFormat, $message['title']))
					. $message['message'];
			}

			$renderHtml.= sprintf(
				$this->alertFormat,
				$this->createAttributesString($defaultAttr),
				$message['message']
			);
		}

		return $renderHtml;
	}

	/**
	 * Return TRUE if exists messages or FALSE otherwise
	 *
	 * @return boolean
	 */
	public function hasMessages()
	{
		if(empty($this->messages)) {
			return false;
		}

		return true;
	}

	public function clearMessages()
	{
		$this->messages = [];
		return $this;
	}

	public function getflashMessages()
	{
		return $this->messages;
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
	}

	/**
	 * @return string
	 */
	public function getAlertFormat()
	{
		return $this->alertFormat;
	}

	/**
	 * @param string $alertFormat
	 */
	public function setAlertFormat($alertFormat)
	{
		$this->alertFormat = $alertFormat;
	}

	/**
	 * @return string
	 */
	public function getDismissButtonFormat()
	{
		return $this->dismissButtonFormat;
	}

	/**
	 * @param string $dismissButtonFormat
	 */
	public function setDismissButtonFormat($dismissButtonFormat)
	{
		$this->dismissButtonFormat = $dismissButtonFormat;
	}
}