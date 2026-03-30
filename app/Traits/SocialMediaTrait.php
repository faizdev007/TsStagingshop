<?php

namespace App\Traits;

use Carbon\Carbon;
use Facebook\Facebook;

trait SocialMediaTrait
{
    private $facebook_app_id;
    private $app_secret;
    private $graph_version;

    public function __construct()
    {
        $this->facebook_app_id = config('facebook.config.fb_app_id');
        $this->app_secret = config('facebook.config.app_secret');
        $this->graph_version = config('facebook.config.default_graph_version');
    }

    public function get_facebook_login($app_id, $app_secret, $graph_version)
    {
        $fb = new Facebook(
            [
                'app_id' => $app_id,
                'app_secret' => $app_secret,
                'default_graph_version' => $graph_version
            ]
        );

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['leads_retrieval', 'manage_pages'];
        $loginUrl = $helper->getLoginUrl(admin_url('social-media/facebook-callback'), $permissions);

        return $loginUrl;
    }

    public function get_lead($id, $access_token)
    {
        $info = file_get_contents('https://graph.facebook.com/v4.0/'.$id.'?access_token='.$access_token);

        $data = json_decode($info, true);

        if($data)
        {
            return $data;
        }
    }

    public function check_access_token($fb_app_id, $app_secret, $graph_version, $access_token)
    {
        // Checks Facebook to see if the Access Token is still valid....
        $fb = new Facebook(
            [
                'app_id' => $fb_app_id,
                'app_secret' => $app_secret,
                'default_graph_version' => $graph_version
            ]
        );

        $oauth = $fb->getOAuth2Client();
        $meta = $oauth->debugToken($access_token);
        $meta->validateAppId($fb_app_id);

        return $meta;
    }
}