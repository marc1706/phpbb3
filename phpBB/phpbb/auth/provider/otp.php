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

namespace phpbb\auth\provider;

use OTPAuthenticate\OTPAuthenticate;

/**
 * OTP authentication provider for phpBB3
 */
class otp extends base
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/**
	 * Constructor for OTP authentication provider
	 *
	 * @param \phpbb\config\config $config
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\user $user
	 * @param string $table_otp_session
	 * @param string $table_otp_tokens
	 */
	public function __construct($config, $db, $user, $table_otp_session, $table_otp_tokens)
	{
		$this->otp_authenticate = new OTPAuthenticate();
	}

	/**
	 * Perform login
	 *
	 * @param string $username Username
	 * @param string $password Password
	 *
	 * @return bool True if login was successful, false if not
	 */
	public function login($username, $password)
	{
		return false;
	}
}
