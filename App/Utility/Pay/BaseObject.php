<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/7/25
 * Time: 14:41
 */

namespace App\Utility\Pay;

use Exception;

/**
 * Class BaseObject
 * @package App\Utility\Pay
 */
class BaseObject
{
    /**
     * @var string
     */
    protected $payUrl;

    /**
     * @var string
     */
    protected $reverseUrl;

    /**
     * @var string
     */
    protected $orderUrl;

    /**
     * @var string
     */
    protected $app_id;

    /**
     * @var string
     */
    protected $mch_id;

    /**
     * @var string
     */
    protected $app_key;

    /**
     * @var int
     */
    protected $timeOut = 10;

    /**
     * BaseObject constructor.
     */
    public function __construct()
    {
        $this->payUrl = 'https://api.mch.weixin.qq.com/pay/micropay';
        $this->reverseUrl = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
        $this->orderUrl = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $this->app_id = 'wx58c72721b878d585';
        $this->mch_id = '1484692262';
        $this->app_key = 'asdsakljlkjasa56465sd4ad56sa4dsa';
    }

    /**
     * POST方式请求curl
     * @param $url
     * @param $data
     * @param $time
     * @return bool|string
     */
    protected function curl_post($url, $data, $time)
    {
        // 初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $time); // 设置超时时长
        curl_setopt($ch, CURLOPT_URL, $url); // 抓取指定网页
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //post提交数据
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            // 显示报错信息；终止继续执行
            curl_close($ch);
            return false;
        }
        $status = curl_getinfo($ch);
        curl_close($ch);
        if (intval($status["http_code"]) == 200) {
            return $res;
        }
        return false;
    }


    /**
     * 生成随机字符串
     * @param int $length 生成的字符串长度
     * @return string
     */
    protected function create_rand_str($length)
    {
        $letters_init = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $letters = str_shuffle($letters_init);
        $total = strlen($letters) - $length;
        $start = mt_rand(0, $total);
        $rand_str = substr($letters, $start, $length);
        return $rand_str;
    }


    /**
     * 生成签名
     * @param $data
     * @param string $signType
     * @return string
     * @throws Exception
     */
    public function makeSign($data, $signType = "MD5")
    {
        $data = array_filter($data);
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = http_build_query($data);
        $string = urldecode($string);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->app_key;
        //签名步骤三：MD5加密或者HMAC-SHA256
        if ($signType == "MD5") {
            $string = md5($string);
        } else if ($signType == "HMAC-SHA256") {
            $string = hash_hmac("sha256", $string, $this->app_key);
        } else {
            pay_log("签名类型不支持");
            throw new Exception("签名类型不支持！");
        }
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    /**
     *  撤销订单API接口
     * @param $out_trade_no
     * @param int $timeOut
     * @return bool|string
     * @throws Exception
     */
    protected function reverse($out_trade_no, $timeOut = 6)
    {
        $config['appid'] = $this->app_id;
        $config['mch_id'] = $this->mch_id;
        $config['nonce_str'] = $this->create_rand_str(32);
        $config['out_trade_no'] = $out_trade_no;
        $config['sign'] = $this->makeSign($config);
        $postXml = arrayToXml($config);
        $response = $this->curl_post($this->reverseUrl, $postXml, $timeOut);
        return $response;
    }

    /**
     * 查询订单情况
     * @param string $out_trade_no 商户订单号
     * @param int $successCode 查询订单结果
     * @return int 0 订单不成功，1表示订单成功，2表示继续等待
     */
    protected function query($out_trade_no, &$successCode)
    {
        try {
            $result = $this->orderQuery($out_trade_no);
        } catch (Exception $e) {
            pay_log(json_encode($e->getMessage()));
            return $e->getMessage();
        }
        if ($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            //支付成功
            if ($result["trade_state"] == "SUCCESS") {
                $successCode = 1;
                return $result;
            } //用户支付中
            else if ($result["trade_state"] == "USERPAYING") {
                $successCode = 2;
                return false;
            }
        }
        //如果返回错误码为“此交易订单号不存在”则直接认定失败
        if ($result["err_code"] == "ORDERNOTEXIST") {
            $successCode = 0;
        } else {
            //如果是系统错误，则后续继续
            $successCode = 2;
        }
        return false;
    }

    /**
     * 订单api接口查询
     * @param $out_trade_no
     * @param int $timeOut
     * @return mixed
     * @throws Exception
     */
    public function orderQuery($out_trade_no, $timeOut = 6)
    {
        if (!$out_trade_no) {
            throw new Exception("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        }
        $config['appid'] = $this->app_id;
        $config['mch_id'] = $this->mch_id;
        $config['nonce_str'] = $this->create_rand_str(32);
        $config['out_trade_no'] = $out_trade_no;
        $config['sign'] = $this->makeSign($config);
        $postXml = arrayToXml($config);
        $response = $this->curl_post($this->orderUrl, $postXml, $timeOut);
        return xmlToArray($response);
    }

    /**
     * 获取毫秒级别的时间戳
     */
    protected function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }

    /**
     * @param $message
     * @return array
     */
    protected function success($message)
    {
        return [
            'status' => true,
            'message' => $message
        ];
    }

    /**
     * @param $message
     * @return array
     */
    protected function error($message)
    {
        return [
            'status' => false,
            'message' => $message
        ];
    }
}