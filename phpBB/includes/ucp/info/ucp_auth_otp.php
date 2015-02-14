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

class ucp_auth_otp_info
{
	function module()
	{
		return array(
			'filename'	=> 'ucp_auth_otp',
			'title'		=> 'UCP_AUTH_OTP',
			'modes'		=> array(
				'auth_otp'	=> array('title' => 'UCP_AUTH_OTP_MANAGE', 'auth' => 'authmethod_otp', 'cat' => array('UCP_PROFILE')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
