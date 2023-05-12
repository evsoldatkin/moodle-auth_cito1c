<?php
defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/authlib.php';

class auth_plugin_cito1c extends auth_plugin_base
{
    public function __construct()
    {
        $this->authtype = 'cito1c';
        $this->config = get_config('auth_cito1c');
    }
    
    function can_be_manually_set()
    {
        return true;
    }
    
    function can_change_password()
    {
        return true;
    }
    
    function can_reset_password()
    {
        return true;
    }
    
    function user_confirm($username, $confirmsecret = null)
    {
        global $DB;
        $user = get_complete_user_data('username', $username);
        if (!empty($user))
        {
            if ($user->confirmed)
                return AUTH_CONFIRM_ALREADY;
            else
            {
                $DB->set_field('user', 'confirmed', 1, ['id' => $user->id]);
                return AUTH_CONFIRM_OK;
            }
        }
        else
            return AUTH_CONFIRM_ERROR;
    }

    function user_login($username, $password)
    {
        global $CFG, $DB;
        $params = [
            'mnethostid' => $CFG->mnet_localhost_id,
            'username' => $username
        ];
        $user = $DB->get_record('user', $params);
        if (!$user)
            return false;
        if (!validate_internal_user_password($user, $password))
            return false;
        if ($password === 'changeme')
            set_user_preference('auth_forcepasswordchange', true, $user->id);
        return true;
    }

    function user_update_password($user, $newpassword)
    {
        $user = get_complete_user_data('id', $user->id);
        return update_internal_user_password($user, $newpassword);
    }
}
