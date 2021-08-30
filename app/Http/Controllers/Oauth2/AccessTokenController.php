<?php

namespace App\Http\Controllers\Oauth2;

use App\Http\Controllers\Controller;
use App\Models\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccessTokenController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getAccessToken(Request $request): JsonResponse
    {
        $http = new Client;

        try {
            $requestOauth2Token = $http->post(config('app.url').'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('auth.grant_id'),
                    'client_secret' => config('auth.grant_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);
        } catch(\Exception $error) {
            return (new Json())->apiResponseJsonError($error->getCode(), $error->getMessage());
        }

        $tokenJson = json_decode((string) $requestOauth2Token->getBody(), true);

        $tokenJsonForResponse = [
            'user_id' => $request->email,
            'token_type' => $tokenJson['token_type'],
            'expires' => $tokenJson['expires_in'],
            'access_token' => $tokenJson['access_token'],
            'refresh_token' => $tokenJson['refresh_token'],
        ];

        return (new Json())->apiResponseJson('ok', $tokenJsonForResponse);
    }
}
