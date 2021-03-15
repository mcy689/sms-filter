<?php

namespace Mcy689\SmsFilter;
abstract class BankBase
{
    const PAY = 1;          //支付
    const TRANSFERS = 2;    //提现
    const AUTH_CODE = 3;    //验证码
    const OPERATOR = 4;     //运营商短信
    protected $message = '';

    public function __construct($message)
    {
        $this->message = $message;
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
     * 验证码
     * @return mixed
     */
    abstract protected function authCode();

    /**
     * 公共返回
     * @param $card
     *          卡尾号
     * @param $amount
     *          金额，单位：分
     * @param $bank
     *          银行名称
     * @param $balance
     *          余额
     * @param $type
     *          类型，支付还是提现
     * @param $isBalance
     *          是否成功获取余额
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
     * 短信验证码，公共返回
     * @param $bank
     * @return array
     */
    protected function bankCode($bank)
    {
        return $this->back(0, 0, $bank, 0, self::AUTH_CODE);
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