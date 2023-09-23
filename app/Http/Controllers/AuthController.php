<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $rulesInputDataUser = [
        'username' => 'required',
        'password'  => 'required'
    ];
    private function getDataWithToken($token)
    {
        $user = Auth::user();
        return [
            'username' => $user->username,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }

    private function getDataInputs($inputs)
    {
        return   [
            'username' => (empty($inputs['username']) ? '' : $inputs['username']),
            'password' => (empty($inputs['password']) ? '' : $inputs['password'])
        ];
    }

    private function addDataUserRequest($request, $data)
    {
        $request->request->add(['username' => $data['username']]);
        $request->request->add(['password' => $data['password']]);
    }

    public function login(Request $request)
    {
        try {
            $inputs = json_decode($request->getContent(), true);
            $data = $this->getDataInputs($inputs);
            $this->addDataUserRequest($request, $data);
            $this->validate($request, $this->rulesInputDataUser);
            $data = $request->only('username', 'password');
            if (!$token = JWTAuth::attempt($data)) {
                throw new Exception('Not Acceptable', 401);
            }
            return new ResponseResource($this->getDataWithToken($token));
        } catch (Exception $ex) {
            $code = $ex->getCode();
            return response()->json(
                ResponseResource::sendMsg($ex->getMessage()),
                $code == 401 ? $code : 404
            );
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = JWTAuth::getToken();
        try {
            JWTAuth::invalidate($token);
            return response()->json(ResponseResource::sendMsg('Successfully logged out'), 200);
        } catch (JWTException $ex) {
            return response()->json(ResponseResource::sendMsg('Fail logout, please try again!'), 404);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $tokenRefresh = JWTAuth::refresh($token);
            return new ResponseResource($this->getDataWithToken($tokenRefresh));
        } catch (TokenExpiredException $ex) {
            return response()->json(ResponseResource::sendMsg('Need to login again please (expired)!'), 401);
        } catch (TokenBlacklistedException $ex) {
            return response()->json(ResponseResource::sendMsg('Need to login again please (blacklist)!'), 404);
        }
    }

    public function userSession()
    {
        $user = Auth::user();
        $data = [
            'username' => $user->username,
            'created_at' => $user->created_at,
            'updated_at' =>  $user->updated_at
        ];
        return new ResponseResource($data);
    }
}
