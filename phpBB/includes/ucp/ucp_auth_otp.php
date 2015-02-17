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

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class ucp_auth_otp
{
	/**
	* @var string
	*/
	public $u_action;

	/**
	* Generates the ucp_auth_otp page and handles the two factor auth process
	*
	* @param	int		$id
	* @param	string	$mode
	*/
	public function main($id, $mode)
	{
		global $request, $template, $phpbb_container, $user;

		$error = array();

		/* @var $provider_collection \phpbb\auth\provider_collection */
		$provider_collection = $phpbb_container->get('auth.provider_collection');
		$auth_provider = $provider_collection->get_provider();

		// confirm that the auth provider supports this page
		$otp_data = $auth_provider->get_user_otp_data();
		if ($otp_data === null)
		{
			$error[] = 'UCP_AUTH_OTP_NOT_SUPPORTED';
		}

		$s_hidden_fields = array();
		add_form_key('ucp_auth_otp');

		$submit	= $request->variable('submit', false, false, \phpbb\request\request_interface::POST);

		// This path is only for primary actions
		if (!sizeof($error) && $submit)
		{
			if (!check_form_key('ucp_auth_otp'))
			{
				$error[] = 'FORM_INVALID';
			}

			if (!sizeof($error))
			{
				// Any post data could be necessary for auth (un)linking
				$link_data = $request->get_super_global(\phpbb\request\request_interface::POST);

				// The current user_id is also necessary
				$link_data['user_id'] = $user->data['user_id'];

				// Tell the provider that the method is auth_link not login_link
				$link_data['link_method'] = 'auth_link';

				if ($request->variable('link', 0, false, \phpbb\request\request_interface::POST))
				{
					$error[] = $auth_provider->link_account($link_data);
				}
				else
				{
					$error[] = $auth_provider->unlink_account($link_data);
				}

				// Template data may have changed, get new data
				$provider_data = $auth_provider->get_auth_link_data();
			}
		}

		if (!empty($otp_data) && !empty($otp_data['secret']))
		{
			$template->assign_vars(array(
				'OTP_TYPE'		=> $user->lang($otp_data['user_otp_counter'] > 0 ? 'UCP_AUTH_OTP_HOTP' : 'UCP_AUTH_OTP_TOTP'),
				'OTP_COUNTER'	=> $otp_data['user_otp_counter'],
			));
		}

		$s_hidden_fields = build_hidden_fields($s_hidden_fields);

		// Replace "error" strings with their real, localised form
		$error = array_map(array($user, 'lang'), $error);
		$error = implode('<br />', $error);

		$template->assign_vars(array(
			'ERROR'	=> $error,

			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			'S_UCP_ACTION'		=> $this->u_action,
		));

		$this->tpl_name = 'ucp_auth_otp';
		$this->page_title = 'UCP_AUTH_OTP';
	}
}
