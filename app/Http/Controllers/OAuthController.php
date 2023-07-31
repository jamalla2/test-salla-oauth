<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Salla\OAuth2\Client\Provider\Salla;

class OAuthController extends Controller
{
    private $provider;

    public function __construct()
    {
        $this->provider =  new Salla([
            'clientId'     => env('SALLA_OAUTH_CLIENT_ID'), // The client ID assigned to you by Salla
            'clientSecret' => env('SALLA_OAUTH_CLIENT_SECRET'), // The client password assigned to you by Salla
            'redirectUri'  => env('SALLA_OAUTH_CLIENT_REDIRECT_URI'), // the url for current page in your service
        ]);;
    }

    public function redirect()
    {
        // die($this->provider->getAuthorizationUrl());
        return redirect($this->provider->getAuthorizationUrl());
    }

    public function callback(Request $request)
    {
        // Try to obtain an access token by utilizing the authorisations code grant.
        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->code ?? ''
            ]);

            /** @var \Salla\OAuth2\Client\Provider\SallaUser $user */
            $user = $this->provider->getResourceOwner($token);
            // echo 'User ID: '.$user->getId()."<br>";
            // echo 'User Name: '.$user->getName()."<br>";
            // echo 'Store ID: '.$user->getStoreID()."<br>";
            // echo 'Store Name: '.$user->getStoreName()."<br>";

            $request->user()->token()->create([
                'access_token'  => $token->getToken(),
                'expires_in'    => $token->getExpires(),
                'refresh_token' => $token->getRefreshToken()
            ]);


            return redirect('/dashboard');
        } catch (IdentityProviderException $e) {
            return redirect('/dashboard')->withStatus($e->getMessage());
        }
    }
}
