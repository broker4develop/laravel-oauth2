<?php

namespace App\Http\Controllers\Oauth2;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccessTokenController extends Controller
{
    public function getAccessToken(Request $request)
    {
        $http = new Client;
        $user = new User();

        $user = $user->where('email',$request->email)->first();
        if (empty($user)) {
            return dd('not Found');
        }

        try {
            $requestOauth2Token = $http->post(config('app.url').'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('auth.grant_id'),
                    'client_secret' => config('auth.grant_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);
        } catch(\Exception $ex) {
            return dd('notAuthorized');
        }

        $userData = array(
            'id' => $user->uuid,
            'fullName' => $user->name,
            'newsletter' => $user->newsletter,
            'profile' => Storage::disk('profile')->url($user->profile),
        );

        $tokenJson = json_decode((string) $requestOauth2Token->getBody(), true);

        $tokenJsonForReturn = [
            'tokenType' => $tokenJson['token_type'],
            'expiresIn' => $tokenJson['expires_in'],
            'accessToken' => $tokenJson['access_token'],
            'refreshToken' => $tokenJson['refresh_token'],
        ];
        $merge = array_merge($userData,$tokenJsonForReturn);

        return response()->json([
            'errorClass' => 'noError',
            'resultCode' => 'ok',
            'errorText' => '',
            'reply' => $merge,
        ]);
    }
}
