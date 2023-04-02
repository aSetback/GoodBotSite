<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;
use Closure;

class OAuth
{
  // API URLs
  private $authorizeURL   = 'https://discord.com/api/oauth2/authorize';
  private $tokenURL       = 'https://discord.com/api/oauth2/token';
  private $apiURLBase     = 'https://discord.com/api/users/@me';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * 
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   

        if ($request->query('code')) {
            session(['code' => $request->query('code')]);
        }

        if (!session()->get('user')) {
          if (session()->get('code')) {
            // Retrieve our access token
            $token = $this->getToken(session()->get('code'));
            if (!property_exists($token, 'access_token')) {
              return $this->getAuthorization($request);
            }
            session(['token' => $token->access_token]);

            // Retrieve user information
            $user = $this->apiRequest($this->apiURLBase);
            session(['user' => $user]);

            // Retrieve guild information
            $guilds = $this->apiRequest($this->apiURLBase . '/guilds');
            usort($guilds, function($a, $b) { return $a->name <=> $b->name; });
            session(['guilds' => $guilds]);

            return redirect($request->query('state'));
          } else {
            return $this->getAuthorization($request);
          }

        }
        return $next($request);
    }

    // Redirect the user to the auth URL
    function getAuthorization($request) {
      $params = [
        'client_id' => config('oauth.clientId'),
        'redirect_uri' => config('oauth.redirectUrl'),
        'response_type' => 'code',
        'scope' => 'identify guilds',
        'state' => $request->path()
      ];
    
      return redirect($this->authorizeURL . '?' . http_build_query($params));
    }

    // Get our token using the auth code
    function getToken($code) {
      $token = $this->apiRequest($this->tokenURL, [
        "grant_type" => "authorization_code",
        'client_id' => config('oauth.clientId'),
        'client_secret' => config('oauth.clientSecret'),
        'redirect_uri' => config('oauth.redirectUrl'),
        'code' => session()->get('code')
      ]);
      return $token;
    }

    // Make an API request
    function apiRequest($url, $post = FALSE, $headers = []) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
            
        if ($post) {
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }
      
        $headers[] = 'Accept: application/json';
        if (session()->get('token')) {
          $headers[] = 'Authorization: Bearer ' . session()->get('token');
        }
      
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);      
        $response = curl_exec($ch);
        return json_decode($response);
      }
      
}
