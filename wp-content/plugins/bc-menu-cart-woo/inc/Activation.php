<?php

/**
 * Handle all activations for BC plugins
 * The name is added for uniqueness
 *
 * Always put this in a subfolder of the plugin root
 */


namespace BinaryCarpenter\BC_MNC;

use BinaryCarpenter\BC_MNC\Config as Config;


class Activation
{

    public static function is_activated()
    {
    	//call this to make sure that license details are saved to site option (some early users don't have this)
        return get_transient(Config::KEY_CHECK_OPTION) === 'valid';
    }
    public static function activate()
    {
        if (self::is_activated())
        {
            error_log('License is already activated, skip checking futher');
            $to_user_data['status'] = 'success';
            $to_user_data['message'] = 'License was successfully activated';
            return $to_user_data;
        }

        //get license key from options
        $license_key = get_option(Config::LICENSE_KEY_OPTION, '');

        //If license key is not set in options, request the user to activate the license first
        if ($license_key === '')
        {
            error_log('License key is not set');
            $to_user_data['status'] = 'error';
            $to_user_data['message'] = 'NO_LICENSE_KEY';
            return $to_user_data;
        } else {
            error_log('License key is set, try to activate it');
            $remote_activation = self::remote_activate_license($license_key);
            return $remote_activation;
        }

    }


    public static function remote_activate_license($key) {
        $activation_body = array(
            'licenseKey' => $key,
            'machineID' => get_site_url(),
            'versionNumber' => Config::PLUGIN_VERSION_NUMBER,
        );

        error_log('Activation body: ' . print_r($activation_body, true));
        $response = wp_remote_post( Config::LICENSE_CHECK_URL, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array("Content-Type" => "application/json"),
            'body'        => json_encode($activation_body)
            ));
        error_log('Response: ' . print_r($response, true));
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            error_log("Something went wrong with remote activation: $error_message");
            return ['result' => false, 'message' => $error_message];
        } else {
            $license = json_decode($response['body']);
            error_log('License activation return OK: ' . print_r($license, true));

            if ($license->result) {
                error_log('License is valid, save it to options and set transient (valid for 30 days)');
                set_transient(Config::KEY_CHECK_OPTION, 'valid', 60 * 60 * 24 * 30);
                update_option(Config::LICENSE_KEY_OPTION, $key);
                return ['result' => true, 'message' => 'License activated'];
            } else {
                error_log('License is not valid, delete it from options and set transient (valid for 30 days)');
                delete_option(Config::LICENSE_KEY_OPTION);
                return ['result' => false, 'message' => $license->message];
            }
        }
    }

    public static function activation_callback() {
        $license_key = $_POST['license_key'];
        $remote_activation = self::remote_activate_license($license_key);
        wp_send_json($remote_activation);
        wp_die();
    }
}
