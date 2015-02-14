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

namespace phpbb\db\migration\data\v31x;

class otp_authenticate extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v313rc2');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'user_otp_secret'		=> array('VCHAR:255', ''),
					'user_otp_counter'		=> array('UINT', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'remove_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'user_otp_secret',
					'user_otp_counter',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			array('module.add', array(
				'ucp',
				'UCP_PROFILE',
				array(
					'module_basename'	=> 'ucp_auth_otp',
					'modes'				=> array('auth_otp'),
				),
			)),
		);
	}
}
