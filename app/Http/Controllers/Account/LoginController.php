<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Oauth2\AccessTokenController;
use App\Models\Json;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $validator = $this->isValidate($request);
        if ($validator->fails()) return (new Json())->apiResponseJsonError(400, '', $validator->errors()->all());

        $user = (new User())->existsUser($request->email);
        if (!$user) return (new Json())->apiResponseJsonError(400, 'Not Found User');

        if (Auth::attempt(['email' => $request->email,'password' => $request->password])) {
            return app(AccessTokenController::class)->getAccessToken($request);
        } else {
            return (new Json())->apiResponseJsonError(400, 'Not Found User');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function isValidate(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type'
        ]);
    }
}
