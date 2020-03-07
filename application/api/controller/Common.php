<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Common extends Controller
{
    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function onLogin(Request $request)
    {
        try{
            $code = $request->param('code');
            $appId = config('wechat.app_id');
            $secret = config('wechat.secret');
            $grant_type = 'authorization_code';
            $client = new Client();
            $res = $client->request('GET',"https://api.weixin.qq.com/sns/jscode2session?appid=$appId&secret=$secret&js_code=$code&grant_type=$grant_type");
            $openid = json_decode($res->getBody()->getContents(),true);
            $uid = $this->getUidByOpenId($openid['openid']);
            $jwt = $this->getToken($uid);
            return json([
                'msg'=>'success',
                'token'=>$jwt
            ]);
        }
        catch (RequestException $e){
            return json(['msg'=>$e->getResponse()->getBody()->getContents()]);
        }
    }

    /**
     * @param string $openid
     * @return int|mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getUidByOpenId($openid='')
    {
        if($openid){
            $user_id = session('user_'.$openid);
            if($user_id){
                return $user_id;
            }else{
                $member = model('admin/Member');
                $user = $member->field('id')->where('openid','=',$openid)->find();
                if($user['id']>0){
                    session('user_'.$openid,$user['id']);
                    return $user['id'];
                }else{
                    $user_id = $member->insertGetId([
                        'openid'=>$openid,
                        'create_time'=>_time()
                    ]);
                    session('user_'.$openid,$user_id);
                    return $user_id;
                }
            }
        }else{
            return 0;
        }
    }

    /**
     * @param int $uid
     * @return string|null
     */
    protected function getToken($uid=0)
    {
        if($uid>0){
            $jwt = session('user_token_'.$uid);
            if(!$jwt){
                $token = [
                    //"iss" => "", //签发者 可以为空
                    //"aud" => "", //面象的用户 可以为空
                    "iat" => time(), //签发时间
                    "nbf" => time(), //生效时间
                    "exp" => time()+1800, //过期时间
                    'uid' => $uid
                ];
                $jwt = JWT::encode($token,config('jwt.jwt_key'),"HS256"); //根据参数生成token
                session('user_token_'.$uid,$jwt);
            }
            return $jwt;
        }else{
            return $uid;
        }
    }
}