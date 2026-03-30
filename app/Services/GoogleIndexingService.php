<?php



namespace App\Services;



use Illuminate\Support\Facades\Log;

use Exception;
use Illuminate\Support\Str;

class GoogleIndexingService

{

    private $credentials;

    

    public function __construct()

    {

        $credentialsPath = storage_path('app/google-search-console.json');

        if (!file_exists($credentialsPath)) {

            throw new Exception('Google credentials file not found');

        }

        

        $this->credentials = json_decode(file_get_contents($credentialsPath), true);

        if (!$this->credentials || !isset($this->credentials['private_key'])) {

            throw new Exception('Invalid Google credentials format');

        }



        // Clean up the private key

        $this->credentials['private_key'] = str_replace('\n', "\n", $this->credentials['private_key']);

    }

    

    public function requestIndexing($url)

    {

        try {

            $accessToken = $this->getAccessToken();

            

            $ch = curl_init('https://indexing.googleapis.com/v3/urlNotifications:publish');

            if (!$ch) {

                throw new Exception('Failed to initialize cURL');

            }

            

            $postData = json_encode([

                'url' => $url,

                'type' => 'URL_UPDATED'

            ]);

            

            curl_setopt_array($ch, [

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_POST => true,

                CURLOPT_POSTFIELDS => $postData,

                CURLOPT_HTTPHEADER => [

                    'Content-Type: application/json',

                    'Authorization: Bearer ' . $accessToken

                ]

            ]);

            

            $response = curl_exec($ch);

            $error = curl_error($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            

            curl_close($ch);

            

            if ($error) {

                throw new Exception("cURL Error: " . $error);

            }

            

            $result = json_decode($response, true);

            

            if ($httpCode !== 200) {

                Log::error('Google Indexing API Error', [

                    'response' => $response,

                    'httpCode' => $httpCode

                ]);

                

                $message = isset($result['error']['message']) 

                    ? $result['error']['message'] 

                    : 'Failed to submit URL. Status Code: ' . $httpCode;

                    

                throw new Exception($message);

            }

            

            return $result;

            

        } catch (Exception $e) {

            Log::error('Google Indexing Service Error', [

                'message' => $e->getMessage(),

                'trace' => $e->getTraceAsString()

            ]);

            throw $e;

        }

    }

    

    private function getAccessToken()

    {

        try {

            $jwt = $this->generateJWT();

            

            $ch = curl_init('https://oauth2.googleapis.com/token');

            curl_setopt_array($ch, [

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_POST => true,

                CURLOPT_POSTFIELDS => http_build_query([

                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',

                    'assertion' => $jwt

                ]),

                CURLOPT_HTTPHEADER => [

                    'Content-Type: application/x-www-form-urlencoded'

                ]

            ]);

            

            $response = curl_exec($ch);

            $error = curl_error($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            

            curl_close($ch);

            

            if ($error) {

                throw new Exception("Token request failed: " . $error);

            }

            

            $result = json_decode($response, true);

            

            if ($httpCode !== 200 || !isset($result['access_token'])) {

                Log::error('Token Error Response', ['response' => $response]);

                throw new Exception(

                    isset($result['error_description']) 

                        ? $result['error_description'] 

                        : 'Failed to get access token'

                );

            }

            

            return $result['access_token'];

        } catch (Exception $e) {

            Log::error('Access Token Error', [

                'error' => $e->getMessage(),

                'trace' => $e->getTraceAsString()

            ]);

            throw $e;

        }

    }

    

    private function generateJWT()

    {

        $header = [

            'alg' => 'RS256',

            'typ' => 'JWT'

        ];

        

        $time = time();

        $payload = [

            'iss' => $this->credentials['client_email'],

            'scope' => 'https://www.googleapis.com/auth/indexing',

            'aud' => 'https://oauth2.googleapis.com/token',

            'exp' => $time + 3600,

            'iat' => $time

        ];

        

        $base64Header = $this->base64UrlEncode(json_encode($header));

        $base64Payload = $this->base64UrlEncode(json_encode($payload));

        

        $privateKey = $this->parsePrivateKey();

        if (!$privateKey) {

            throw new Exception('Failed to parse private key');

        }



        $signature = '';

        if (!openssl_sign(

            $base64Header . '.' . $base64Payload,

            $signature,

            $privateKey,

            OPENSSL_ALGO_SHA256

        )) {

            throw new Exception('Failed to sign JWT: ' . openssl_error_string());

        }

        

        $base64Signature = $this->base64UrlEncode($signature);

        

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;

    }

    

    private function parsePrivateKey()

    {

        try {

            $key = $this->credentials['private_key'];

            

            // Key should already be in PEM format, but let's verify

            if (strpos($key, '-----BEGIN PRIVATE KEY-----') === false) {

                $key = "-----BEGIN PRIVATE KEY-----\n" . 

                       chunk_split($key, 64, "\n") . 

                       "-----END PRIVATE KEY-----\n";

            }

            

            $privateKey = openssl_pkey_get_private($key);

            if (!$privateKey) {

                throw new Exception('Invalid private key format: ' . openssl_error_string());

            }

            

            return $privateKey;

        } catch (Exception $e) {

            Log::error('Private Key Error', [

                'error' => $e->getMessage()

            ]);

            throw $e;

        }

    }

    

    private function base64UrlEncode($data)

    {

        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

    }

}

