<?php
namespace Util\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container as SessionContainer;

class Messages extends AbstractPlugin
{
	const SESSION_NAME = 'sisadmin_cms_messages';

	/**
	 * Array for standard messages
	 *
	 * @var array
	 */
	protected $messages = array();

	/**
	 * Session container for flash messages
	 *
	 * @var SessionContainer
	 */
	protected $sessionContainer;

	/**
	 * @var boolean
	 */
	protected $flashMessageAdded;

	public function getMessages()
	{
		return $this->messages;
	}

	/**
	 * Getter for the session container
	 *
	 * This function initialises the session container on demand
	 *
	 * @return SessionContainer
	 */
	public function getSessionContainer()
	{
		if (!$this->sessionContainer) {
			$this->sessionContainer = new SessionContainer(self::SESSION_NAME);
		}

		return $this->sessionContainer;
	}

	/**
	 * Returns an array containing both the standard messages
	 * and any flash ones
	 *
	 * @return array
	 */
	public function getMergedMessages()
	{
		$sessionContainer = $this->getSessionContainer();
		if (!isset($sessionContainer->messages) || !is_array($sessionContainer->messages)) {
			$sessionMessages = array();
		} else {
			$sessionMessages = $sessionContainer->messages;
		}

		return array_merge($sessionMessages, $this->messages);
	}

	/**
	 * Adds a message
	 *
	 * @param  string $type
	 * @param  string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	protected function addMessage($type, $message, $title=null)
	{
		if (empty($type)) {
			throw new \Exception('Invalid message type supplied');
		}

		$content = [
			'type' => $type,
			'message' => $message,
			'title' => $title
		];

		$this->messages[] = $content;

		return $this;
	}

	/**
	 * Adds an danger message
	 *
	 * @param  string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	public function danger($message, $title=null) {
		return $this->addMessage('danger', $message, $title);
	}

	/**
	 * Adds an error message
	 *
	 * @param  string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	public function error($message, $title=null)
	{
		return $this->danger($message, $title);
	}

	/**
	 * Adds a success message
	 *
	 * @param string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	public function success($message, $title=null)
	{
		return $this->addMessage('success', $message);
	}

	/**
	 * Adds an warning message
	 *
	 * @param  string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	public function warning($message, $title=null) {
		return $this->addMessage('warning', $message, $title);
	}

	/**
	 * Adds an white message
	 *
	 * @param  string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	public function white($message, $title=null) {
		return $this->addMessage('white', $message, $title);
	}

	/**
	 * Adds an black message
	 *
	 * @param  string $message
	 * @param string $title
	 *
	 * @return Messages
	 */
	public function black($message, $title=null) {
		return $this->addMessage('black', $message, $title=null);
	}

	/**
	 * Adds a flash message (stored in the session container)
	 *
	 * @param string $type
	 * @param string $message
	 * @param string $title
	 */
	protected function addFlashMessage($type, $message, $title=null)
	{
		if (empty($type)) {
			throw new \Exception('Invalid flash message type supplied');
		}

		$container = $this->getSessionContainer();

		if (!$this->flashMessageAdded) {
			$container->setExpirationHops(1, null);
			$this->flashMessageAdded = true;
		}

		if (!isset($container->messages)) {
			$container->messages = array();
		}

		$content = [
			'type' => $type,
			'message' => $message,
			'title' => $title
		];
		$container->messages[] = $content;

		return $this;
	}

	/**
	 * Add a flash error message
	 *
	 * @param  string $message
	 * @return Messages
	 */
	public function flashError($message, $title=null)
	{
		return $this->addFlashMessage('error', $message, $title);
	}

	/**
	 * Add a flash success message
	 *
	 * @param  string $message
	 * @return Messages
	 */
	public function flashSuccess($message, $title=null)
	{
		return $this->addFlashMessage('success', $message, $title);
	}

	/**
	 * Add a flash warning message
	 *
	 * @param  string $message
	 * @return Messages
	 */
	public function flashWarning($message, $title=null)
	{
		return $this->addFlashMessage('warning', $message, $title);
	}

	/**
	 * Add a flash danger message
	 *
	 * @param  string $message
	 * @return Messages
	 */
	public function flashDanger($message, $title=null)
	{
		return $this->addFlashMessage('danger', $message, $title);
	}

	/**
	 * Add a flash white message
	 *
	 * @param  string $message
	 * @return Messages
	 */
	public function flashWhite($message, $title=null)
	{
		return $this->addFlashMessage('white', $message, $title);
	}

	/**
	 * Add a flash black message
	 *
	 * @param  string $message
	 * @return Messages
	 */
	public function flashBlack($message, $title=null)
	{
		return $this->addFlashMessage('black', $message, $title);
	}
}