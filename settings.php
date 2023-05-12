<?php
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree)
{
    $settings->add(new admin_setting_heading(
        'auth_cito1c/pluginname',
        new lang_string('description'),
        new lang_string('description', 'auth_cito1c')
    ));
    $authplugin = get_auth_plugin('cito1c');
    display_auth_lock_options(
        $settings,
        $authplugin->authtype,
        $authplugin->userfields,
        get_string('auth_fieldlocks_help', 'auth'),
        false,
        false
    );
}
