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
class otp extends db
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \OTPAuthenticate\OTPAuthenticate */
	protected $otp_authenticate;

	/** @var \phpbb\passwords\manager */
	protected $passwords_manager;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/**
	 * Constructor for OTP authentication provider
	 *
	 * @param \phpbb\config\config $config
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \OTPAuthenticate\OTPAuthenticate $otp_authenticate OTP Authentication class
	 * @param \phpbb\user $user
	 * @param string $table_otp_session
	 * @param string $table_otp_tokens
	 */
	public function __construct($config, $db, OTPAuthenticate $otp_authenticate, \phpbb\passwords\manager $passwords_manager, \phpbb\request\request_interface $request, $user, $table_otp_session, $table_otp_tokens)
	{
		$this->config = $config;
		$this->db = $db;
		$this->otp_authenticate = $otp_authenticate;
		$this->passwords_manager = $passwords_manager;
		$this->request = $request;
		$this->user = $user;
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
		$return = parent::login($username, $password);

		if ($return['status'] !== LOGIN_SUCCESS)
		{
			return $return;
		}

		if (!$this->request->is_set_post('code'))
		{
			if (!isset($return['user_row']['user_secret']) || empty($return['user_row']['user_secret']))
			{
				return $return;
			}
		}

		return false;
	}
}
