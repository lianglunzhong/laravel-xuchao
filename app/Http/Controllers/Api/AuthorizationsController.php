<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\WeappAuthorizationRequest;
use App\Models\User;
use Auth;

class AuthorizationsController extends Controller
{
    public function weappStore(WeappAuthorizationRequest $request)
    {
    	$code = $request->code;

    	// 根据 code 获取微信 opened 和session_key
    	$miniProgram = app('mini_pro');
    	$data = $miniProgram->auth->session($code);

    	// 如果结果错误，说明 code 已过期或不正确，返回 401 错误
    	if (isset($data['errcode'])) {
    		return $this->response->errorUnauthorized('code 不正确');
    	}

    	// 找到 openid 对应的用户
    	$user = User::where('weixin_openid', $data['openid'])->first();

    	$attributes['weixin_session_key'] = $data['session_key'];

    	if (!$user) {
    		$user = new User();
    		$user->weixin_openid = $data['openid'];
    		$user->save();
    	}

    	// 更新用户数据
    	$user->update($attributes);

    	// 为对应用户创建 JWT
    	$token = Auth::guard('api')->fromuser($user);

    	return $this->respondWithToken($token)->setStatusCode(201);
    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    public function update()
    {
        $token = Auth::guard('api')->refresh();

        return $this->respondWithToken($token);
    }
}
