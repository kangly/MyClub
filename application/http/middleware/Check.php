<?php

namespace app\http\middleware;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use think\Request;

class Check
{
    public function handle($request, \Closure $next)
    {
        // 添加中间件执行代码
        $uid = $this->checkToken($request);
        $request->uid = $uid;

        return $next($request);
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    protected function checkToken(Request $request)
    {
        $jwt = $request->param('jwt');
        if($jwt==1){
            $token = $request->param('token');
            if($token){
                try{
                    $key = config('jwt.jwt_key');
                    $jwt_token = JWT::decode($token,$key,["HS256"]); //解密jwt
                    if($request->controller()=='Article'){
                        if($request->action()=='info'){
                            $article_id = $request->param('id');
                            if($article_id>0 && $jwt_token->uid>0){
                                $ArticleVisit = model('admin/ArticleVisit');
                                $visit = $ArticleVisit
                                    ->field('id')
                                    ->where([
                                        ['article_id','=',$article_id],
                                        ['member_id','=',$jwt_token->uid]
                                    ])
                                    ->find();
                                if($visit['id']>0){
                                    //处理
                                }else{
                                    $ArticleVisit->insert([
                                        'article_id'=>$article_id,
                                        'member_id'=>$jwt_token->uid,
                                        'create_time'=>_time()
                                    ]);
                                    //其它处理
                                }
                            }
                        }
                    }
                    return $jwt_token->uid;
                    //return ['code'=>200,'msg'=>'验证通过'];
                }
                catch (ExpiredException $e){
                    echo json_encode(['code'=>1001,'msg'=>'token已过期']);
                    exit;
                }
                catch (\Exception $e){
                    echo json_encode(['code'=>400,'msg'=>$e->getMessage()]);
                    exit;
                }
            }else{
                echo json_encode(['code'=>400,'msg'=>'无效的token']);
                exit;
            }
        }else{
            return true;
        }
    }
}
