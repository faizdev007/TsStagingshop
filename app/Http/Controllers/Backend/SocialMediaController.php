<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\SocialMediaTrait;
use Facebook\Facebook;
use Illuminate\Support\Facades\Log;

class SocialMediaController extends Controller
{
    use SocialMediaTrait;

    private $is_set;
    private $app_id;
    private $app_secret;
    private $graph_version;

    public function __construct()
    {
        $this->moduleTitle = "Social Media";
        $this->is_set = settings('social_leads');
        $this->app_id = config('facebook.config.fb_app_id');
        $this->app_secret = config('facebook.config.app_secret');
        $this->graph_version = config('facebook.config.default_graph_version');

        //Prevent Access if Turned Of....
        if($this->is_set == '0' || !$this->is_set)
        {
            return redirect('/admin')->send();
        }
    }

    public function index()
    {
        // Check if we have an Access Token...
        $access_token = 'EAAjUGAXGwp8BAH1ChxPXQTWZCkgJbDmWGR2cf2y28C4idhBLW5xP1ZAWAq5sRXDr20qDZAN5mWoocTWXLfNrg0slekKQ33f7bZBc962NZAXLUuKt2tq3YCrwVp1IlCpoyiFuL6bKMVZCRzS5so2FwVF89zYc0dMWUZD';
        //$access_token = '';

        if($access_token)
        {
            // Check If Access Token is still valid...
            $token_check = $this->check_access_token($this->app_id, $this->app_secret, $this->graph_version, $access_token);

            if($token_check->getIsValid())
            {
                $login_required = false;
                $carbon = Carbon::instance($token_check->getExpiresAt());
                $expires_at = $carbon->format('jS F Y');
            }
        }
        else
        {
            $login_required = true;
            $expires_at = '';
        }

        return view('backend.social_media.index',
            [
                'facebook_login_url'    => $this->get_facebook_login($this->app_id, $this->app_secret, $this->graph_version),
                'login_required'        => $login_required,
                'expires_at'            => $expires_at,
                'pageTitle'             => $this->moduleTitle
            ]);
    }

    public function facebook_callback(Request $request)
    {
        if (!session_id())
        {
            session_start();
        }

        $fb = new \Facebook\Facebook(
            [
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => $this->graph_version
            ]
        );

        $helper = $fb->getRedirectLoginHelper();

        if(isset($_GET['state']))
        {

            $_SESSION['FBRLH_state'] = $_GET['state'];
        }

        try
        {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e)
        {
            // When Graph returns an error
            Log::error('Facebook Error : '. $e->getMessage());

            // Error....
            $request->session()->flash('message_danger', 'Failed to connect to Facebook, Please try again!');
            return redirect('admin/social-media');

        } catch(\Facebook\Exceptions\FacebookSDKException $e)
        {
            // When validation fails or other local issues
            Log::error('Facebook Error : '. $e->getMessage());

            // Error....
            $request->session()->flash('message_danger', 'Failed to connect to Facebook, Please try again!');
            return redirect('admin/social-media');
        }

        if (! isset($accessToken))
        {
            if ($helper->getError())
            {
                Log::error('Facebook Error : '. $helper->getErrorDescription());

                // Error....
                $request->session()->flash('message_danger', 'Failed to connect to Facebook, Please try again!');
                return redirect('admin/social-media');
            }
            else
            {
                Log::error('Facebook Error : '. 'Bad Request');

                // Error....
                $request->session()->flash('message_danger', 'Failed to connect to Facebook, Please try again!');
                return redirect('admin/social-media');
            }
        }

        // Log In Success...

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->app_id);

        // If you know the user ID this access token belongs to, you can validate it here
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived())
        {
            // Exchanges a short-lived access token for a long-lived one
            try
            {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (\Facebook\Exceptions\FacebookSDKException $e)
            {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }

            var_dump($accessToken->getValue());
        }
        else
        {
            var_dump($accessToken->getValue());
        }
    }
}
