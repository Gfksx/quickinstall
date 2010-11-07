<?php
/**
*
* @package quickinstall
* @version $Id$
* @copyright (c) 2010 Jari Kanerva (tumba25)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_QUICKINSTALL'))
{
	exit;
}

if ($mode == 'update_settings')
{
	// Time to save some settings.
	$qi_config = request_var('qi_config', array('' => ''));

	// make sure qi_config.php is writable
	if (is_writable($quickinstall_path . 'qi_config.cfg'))
	{
		$error = update_settings($qi_config);
	}

	if (empty($error))
	{
		$s_settings_success = true;
		$qi_install = false;
	}
	else
	{
		$s_settings_failure = true;
	}
}

// Temporary store our language.
if (!empty($lang))
{
	$store_lang = $lang;
	unset($lang);
}
else
{
	$store_lang = '';
}
// Generate the language select.
$lang_dir = scandir($quickinstall_path . 'language');
$lang_arr = array();
$s_selected = false;
foreach ($lang_dir as $language)
{
	if (file_exists($quickinstall_path . 'language/' . $language . '/phpbb.' . $phpEx))
	{
		include($quickinstall_path . 'language/' . $language . '/phpbb.' . $phpEx);

		// Default this to English
		if (empty($qi_config['qi_lang']) && $lang['USER_LANG'] == 'en')
		{
			$s_selected = true;
		}
		else if ($lang['USER_LANG'] == $qi_config['qi_lang'])
		{
			$s_selected = true;
		}
		else
		{
			$s_selected = false;
		}

		$template->assign_block_vars('lang_row', array(
			'LANG_CODE' => $lang['USER_LANG'],
			'LANG_NAME' => $lang['USER_LANG_LONG'],
			'S_SELECTED' => $s_selected,
		));
		unset($lang);
	}
}
// And restore our language
$lang = $store_lang;
unset($store_lang);

$template->assign_vars(array(
	'S_BOARDS_WRITABLE' => is_writable($quickinstall_path . 'boards'),
	'S_CACHE_WRITABLE' => is_writable($quickinstall_path . 'cache'),
	'S_CONFIG_WRITABLE' => is_writable($quickinstall_path . 'qi_config.cfg'),
	'S_IN_INSTALL' => $qi_install,
	'S_IN_SETTINGS' => true,
	'S_SETTINGS_SUCCESS' => (!empty($s_settings_success)) ? true : false,
	'S_SETTINGS_FAILURE' => (!empty($s_settings_failure)) ? true : false,

	'ERROR' => (!empty($error)) ? ((!$qi_install) ? $error : '') : '',

	'U_UPDATE_SETTINGS'		=> qi::url('update_settings'),

	'TABLE_PREFIX'	=> htmlspecialchars($qi_config['table_prefix']),
	'SITE_NAME'		=> $qi_config['site_name'],
	'SITE_DESC'		=> $qi_config['site_desc'],
	'ALT_ENV'		=> (!empty($alt_env)) ? $alt_env : false,
	'PAGE_MAIN'		=> false,

	// Config settings
	'CONFIG_ADMIN_EMAIL' => (!empty($qi_config['admin_email'])) ? $qi_config['admin_email'] : '',
	'CONFIG_ADMIN_NAME' => (!empty($qi_config['admin_name'])) ? $qi_config['admin_name'] : '',
	'CONFIG_ADMIN_PASS' => (!empty($qi_config['admin_pass'])) ? $qi_config['admin_pass'] : '',
	'CONFIG_AUTOMOD' => (isset($qi_config['automod'])) ? $qi_config['automod'] : 1,
	'CONFIG_BOARD_EMAIL' => (!empty($qi_config['board_email'])) ? $qi_config['board_email'] : '',
	'CONFIG_BOARDS_DIR' => (!empty($qi_config['boards_dir'])) ? $qi_config['boards_dir'] : 'boards/',
	'CONFIG_COOKIE_DOMAIN' => (!empty($qi_config['cookie_domain'])) ? $qi_config['cookie_domain'] : 'localhost',
	'CONFIG_COOKIE_SECURE' => (!empty($qi_config['cookie_secure'])) ? $qi_config['cookie_secure'] : 0,
	'CONFIG_DB_PREFIX' => (!empty($qi_config['db_prefix'])) ? $qi_config['db_prefix'] : 'qi_',
	'CONFIG_DBHOST' => (!empty($qi_config['dbhost'])) ? $qi_config['dbhost'] : 'localhost',
	'CONFIG_DBMS' => (!empty($qi_config['dbms'])) ? $qi_config['dbms'] : 'mysql',
	'CONFIG_DBPASSWD' => (!empty($qi_config['dbpasswd'])) ? $qi_config['dbpasswd'] : '',
	'CONFIG_DBPORT' => (!empty($qi_config['dbport'])) ? $qi_config['dbport'] : '',
	'CONFIG_DBUSER' => (!empty($qi_config['dbuser'])) ? $qi_config['dbuser'] : '',
	'CONFIG_DEFAULT_LANG' => (!empty($qi_config['default_lang'])) ? $qi_config['default_lang'] : 'en',
	'CONFIG_EMAIL_ENABLE' => (!empty($qi_config['email_enable'])) ? $qi_config['email_enable'] : 0,
	'CONFIG_MAKE_WRITABLE' => (!empty($qi_config['make_writable'])) ? $qi_config['make_writable'] : 0,
	'CONFIG_NO_PASSWORD' => (isset($qi_config['no_dbpasswd'])) ? $qi_config['no_dbpasswd'] : 0,
	'CONFIG_POPULATE' => (isset($qi_config['populate'])) ? $qi_config['populate'] : 0,
	'CONFIG_QI_DST' => (!empty($qi_config['qi_dst'])) ? $qi_config['qi_dst'] : 0,
	'CONFIG_QI_TZ' => (!empty($qi_config['qi_tz'])) ? $qi_config['qi_tz'] : 0,
	'CONFIG_REDIRECT' => (isset($qi_config['redirect'])) ? $qi_config['redirect'] : 1,
	'CONFIG_SERVER_NAME' => (!empty($qi_config['server_name'])) ? $qi_config['server_name'] : 'localhost',
	'CONFIG_SERVER_PORT' => (!empty($qi_config['server_port'])) ? $qi_config['server_port'] : '80',
	'CONFIG_SITE_DESC' => (!empty($qi_config['site_desc'])) ? $qi_config['site_desc'] : 'eviLs testing hood',
	'CONFIG_SITE_NAME' => (!empty($qi_config['site_name'])) ? $qi_config['site_name'] : 'Testing Board',
	'CONFIG_SMTP_AUTH' => (!empty($qi_config['smtp_auth'])) ? $qi_config['smtp_auth'] : 'PLAIN',
	'CONFIG_SMTP_DELIVERY' => (!empty($qi_config['smtp_delivery'])) ? $qi_config['smtp_delivery'] : 0,
	'CONFIG_SMTP_HOST' => (!empty($qi_config['smtp_host'])) ? $qi_config['smtp_host'] : '',
	'CONFIG_SMTP_PASS' => (!empty($qi_config['smtp_pass'])) ? $qi_config['smtp_pass'] : '',
	'CONFIG_SMTP_PORT' => (!empty($qi_config['smtp_port'])) ? $qi_config['smtp_port'] : 25,
	'CONFIG_SMTP_USER' => (!empty($qi_config['smtp_user'])) ? $qi_config['smtp_user'] : '',
	'CONFIG_SUBSILVER' => (isset($qi_config['subsilver'])) ? $qi_config['subsilver'] : 0,
	'CONFIG_TABLE_PREFIX' => (!empty($qi_config['table_prefix'])) ? $qi_config['table_prefix'] : 'phpbb_',
));

// Output page
qi::page_header($user->lang['SETTINGS'], $user->lang['QI_MAIN_ABOUT']);

$template->set_filenames(array(
	'body' => 'settings_body.html')
);

qi::page_footer();

?>