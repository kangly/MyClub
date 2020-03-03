<?php

namespace app\api\controller;

use GuzzleHttp\Exception\RequestException;
use think\Controller;
use think\Request;
use \Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use GuzzleHttp\Client;

class Base extends Controller
{
    public function onLogin(Request $request)
    {
        try{
            $code = $request->param('code');
            $appId = config('wechat.app_id');
            $secret = config('wechat.secret');
            $grant_type = 'authorization_code';
            $client = new Client();
            $res = $client->request('GET',"https://api.weixin.qq.com/sns/jscode2session?appid=$appId&secret=$secret&js_code=$code&grant_type=$grant_type");
            return json([
                'msg'=>'success',
                'data'=>$res->getBody()->getContents()
            ]);
        }
        catch (RequestException $e){
            return json(['msg'=>$e->getResponse()->getBody()->getContents()]);
        }
    }

    public function getToken(Request $request)
    {
        $uid = $request->param('uid');
        $token = [
            "iss" => "", //签发者 可以为空
            "aud" => "", //面象的用户 可以为空
            "iat" => time(), //签发时间
            "nbf" => time(), //生效时间
            "exp" => time()+7200, //过期时间
            'uid' => $uid
        ];
        $jwt = JWT::encode($token,config('jwt.jwt_key'),"HS256"); //根据参数生成token
        return $jwt;
    }

    protected function checkToken($token)
    {
        try{
            $key = config('jwt.jwt_key');
            JWT::decode($token,$key,["HS256"]); //解密jwt
            return ['code'=>200,'msg'=>'成功'];
        }
        catch (ExpiredException $e){
            return json(['code'=>1001,'msg'=>'token已过期']);
        }
        catch (\Exception $e){
            return json(['code'=>400,'msg'=>$e->getMessage()]);
        }
    }
}