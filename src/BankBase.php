<?php

namespace Mcy689\SmsFilter;
abstract class BankBase
{
    const PAY = 1;          //支付
    const TRANSFERS = 2;    //提现
    const AUTH_CODE = 3;    //验证码
    const OPERATOR = 4;     //运营商短信
    protected $message = null;
    protected $bankName = null;

    public function __construct($message)
    {
        $this->message = $message;
        $filter = ['验证码', '短信', '动态密码', '动态码'];
        foreach ($filter as $item) {
            $auth = strpos($this->message, $item);
            if ($auth !== false) {
                return $this->back(0, 0, $this->bankName, 0, self::AUTH_CODE);
            }
        }
    }

    /**
     * 分析支付、提现
     * @return mixed
     */
    abstract public function dispatch();

    /**
     * 提现
     * @return mixed
     */
    abstract protected function transfers();

    /**
     * 下单
     * @return mixed
     */
    abstract protected function pay();

    /**
     * 公共返回
     * @param string $card 卡尾号
     * @param int $amount 金额，单位：分
     * @param string $bank 银行名称
     * @param int $balance 余额
     * @param int $type 短信类型
     * @param bool $isBalance 是否成功获取余额
     * @return array
     */
    protected function back($card, $amount, $bank, $balance, $type, $isBalance = true)
    {
        return ['card' => $card, 'amount' => $amount, 'bank' => $bank, 'balance' => $balance, 'is_balance' => $isBalance, 'type' => $type];
    }

    /**
     * 提现公共返回
     * @param $card
     * @param $amount
     * @param $bank
     * @param $type
     * @param $name
     * @return array
     */
    protected function backTr($card, $amount, $name, $bank, $type)
    {
        return ['card' => $card, 'amount' => $amount, 'bank' => $bank, 'name' => $name, 'type' => $type];
    }

    /**
     * 元转分
     * @param $money
     * @return string|null
     */
    protected function units($money)
    {
        return (int)($money * 100);
    }
}