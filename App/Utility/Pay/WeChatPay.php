<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/7/25
 * Time: 13:37
 */

namespace App\Utility\Pay;

use Exception;

class WeChatPay extends BaseObject
{
    /**
     * 刷卡支付
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function pay(array $data)
    {
        $data['body'] = $data['body'] ?? "睿鼎水吧刷卡支付";
        if (!$data['out_trade_no']) {
            return $this->error("订单号不能为空");
        }
        if (!$data['total_fee']) {
            return $this->error("金额不能为空");
        }
        if (!$data['auth_code']) {
            return $this->error("付款码不能为空");
        }
        $config['appid'] = $this->app_id;
        $config['mch_id'] = $this->mch_id;
        $config['nonce_str'] = $this->create_rand_str(32);
        $data['total_fee'] = ($data['total_fee'] * 100);
        $array = array_merge($data, $config);
        $array['sign'] = $this->makeSign($array);
        $postXml = arrayToXml($array);
        $response = $this->curl_post($this->payUrl, $postXml, $this->timeOut);
        $responseData = xmlToArray($response);
        //判断auth_code 是否有效
        if ($responseData["return_code"] == "SUCCESS" && $responseData["result_code"] == "FAIL" && $responseData["err_code"] == "AUTH_CODE_INVALID") {
            return $this->error($responseData['err_code_des']);
        }
        //支付回调处理
        $notify = $this->notify($array, $response);
        pay_log(json_encode($notify));
        if ($notify['trade_state'] == 'SUCCESS') {
            return $this->success($notify['trade_state_desc']);
        }
        return $this->error($notify['trade_state_desc']);
    }

    /**
     * 支付回调处理
     * @param $array
     * @param $response
     * @return bool|int
     * @throws Exception
     */
    private function notify($array, $response)
    {
        $result = xmlToArray($response);
        //如果返回成功
        if (!array_key_exists("return_code", $result) || !array_key_exists("result_code", $result)) {
            pay_log('接口调用失败');
            throw new Exception("接口调用失败！");
        }
        //取订单号
        $out_trade_no = $array['out_trade_no'];
        //②、接口调用成功，明确返回调用失败
        if ($result["return_code"] == "SUCCESS" && $result["result_code"] == "FAIL" && $result["err_code"] != "USERPAYING" && $result["err_code"] != "SYSTEMERROR") {
            return false;
        }
        //③、确认支付是否成功
        $queryTimes = 10;
        while ($queryTimes > 0) {
            $successResult = 0;
            $queryResult = $this->query($out_trade_no, $successResult);
            //如果需要等待1s后继续
            if ($successResult == 2) {
                sleep(2);
                continue;
            } else if ($successResult == 1) {
                //查询成功
                return $queryResult;
            } else {//订单交易失败
                break;
            }
        }
        //④、次确认失败，则撤销订单
        if (!$this->cancelOrder($out_trade_no)) {
            pay_log('撤销单失败');
            throw new Exception("撤销单失败");
        }
        return false;
    }


    /**
     * 撤销订单，如果失败会重复调用10次
     * @param $out_trade_no
     * @param int $depth
     * @return bool
     */
    public function cancelOrder($out_trade_no, $depth = 0)
    {
        try {
            if ($depth > 10) {
                return false;
            }
            $result = $this->reverse($out_trade_no);
            //接口调用失败
            if ($result["return_code"] != "SUCCESS") {
                return false;
            }
            //如果结果为success且不需要重新调用撤销，则表示撤销成功
            if ($result["result_code"] != "SUCCESS" && $result["recall"] == "N") {
                return true;
            } else if ($result["recall"] == "Y") {
                return $this->cancelOrder($out_trade_no, ++$depth);
            }
        } catch (Exception $e) {
            pay_log(json_encode($e->getMessage()));
        }
        return false;
    }
}