<?php
/**
 * Created by PhpStorm.
 * User: david.li
 * Date: 10/9/18
 * Time: 11:58
 */

namespace App\HttpController;


use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\AbstractInterface\Controller;

abstract class BaseController extends Controller
{
    protected $code = 200;
    protected $message = 'SUCCESS';
    protected $data = [];//返回数据

    /***
     * 初始化执行
     * @param $action
     * @return bool|null
     */
    protected function onRequest($action): ?bool
    {
        /**
         * action 执行的方法
         */
        echo $action, PHP_EOL;
        $this->currentTimestamp = $this->request()->getAttribute('currentTimestamp');
//        $headers = $this->request()->getHeaders();
        # 验证 token
        Logger::getInstance()->console('onRequest ..... '.$this->currentTimestamp);

        return true;
    }




    protected function onException(\Throwable $throwable): void
    {
        var_dump($throwable->getMessage());
        Logger::getInstance()->console($throwable->getMessage().'::onException');
        parent::onException($throwable); // TODO: Change the autogenerated stub
    }

    /***
     * 最后执行的方法
     * @param $actionName
     */
    protected function afterAction($actionName): void
    {
        Logger::getInstance()->console('afterAction ..... '.$actionName);
    }

    protected function actionNotFound($action): void
    {
        $this->setErrorCode(CodeInformation::REQUEST_SPECIFIED_API_METHOD_FAIL, [$action]);
    }

    protected function writeJson($statusCode = 200, $msg = null, $data = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                'code' => $statusCode,
                'message' => $msg,
                'data' => $data
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
            $this->response()->end();
            return true;
        } else {
            return false;
        }
    }

    public function gc()
    {
        # 回收系统资源
        var_dump('BaseController:GC:class :'.static::class.' is recycle to pool');
        parent::gc();
    }

}
