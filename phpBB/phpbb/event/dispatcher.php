<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace phpbb\event;

use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\Event;

/**
* Extension of the Symfony2 EventDispatcher
*
* It provides an additional `trigger_event` method, which
* gives some syntactic sugar for dispatching events. Instead
* of creating the event object, the method will do that for
* you.
*
* Example:
*
*     $vars = array('page_title');
*     extract($phpbb_dispatcher->trigger_event('core.index', compact($vars)));
*
*/
class dispatcher extends ContainerAwareEventDispatcher implements dispatcher_interface
{
	/**
	 * @var bool
	 */
	protected $disabled = false;

	/**
	* {@inheritdoc}
	*/
	public function trigger_event($eventName, $data = array())
	{
		$event = new \phpbb\event\data($data);
		$this->dispatch($eventName, $event);
		return $event->get_data_filtered(array_keys($data));
	}

	/**
	 * {@inheritdoc}
	 */
	public function trigger_event2($eventName, $data = array(),
								   &$arg1 = null, &$arg2 = null, &$arg3 = null, &$arg4 = null, &$arg5 = null, &$arg6 = null,
								   &$arg7 = null, &$arg8 = null, &$arg9 = null, &$arg10 = null, &$arg11 = null, &$arg12 = null,
								   &$arg13 = null, &$arg14 = null, &$arg15 = null, &$arg16 = null, &$arg17 = null, &$arg18 = null,
								   &$arg19 = null, &$arg20 = null, &$arg21 = null, &$arg22 = null, &$arg23 = null, &$arg24 = null,
								   &$arg25 = null, &$arg26 = null, &$arg27 = null, &$arg28 = null, &$arg29 = null, &$arg30 = null)
	{
		$event = new \phpbb\event\data($data);
		$this->dispatch($eventName, $event);
		$data = $event->get_data_filtered(array_keys($data));
		$num_args = func_num_args();
		if ($num_args > 32 || ($num_args - 2) !== sizeof($data))
		{
			trigger_error('incorrect_data');
		}
		for ($i = 1; $i < ($num_args - 1); $i++)
		{
			$arg{$i} = array_shift($data);
		}

		//return $event->get_data_filtered(array_keys($data));
	}

	/**
	 * {@inheritdoc}
	 */
	public function dispatch($eventName, Event $event = null)
	{
		if ($this->disabled)
		{
			return $event;
		}

		return parent::dispatch($eventName, $event);
	}

	/**
	 * {@inheritdoc}
	 */
	public function disable()
	{
		$this->disabled = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function enable()
	{
		$this->disabled = false;
	}
}
