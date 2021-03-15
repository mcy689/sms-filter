<?php
/*
 * This file is part of the mcy689/smsFilter.
 *
 * (Author) mcy689 <nideshijian@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Mcy689\SmsFilter\Template;

use  Mcy689\SmsFilter\BankBase;
use Mcy689\SmsFilter\Exceptions\MatchException;

/**
 * 邮储银行
 * Class PINGAN
 * @package Lib\Pay\Lib\CoralAliPay
 */
class PSBC extends BankBase
{
    private $bankName = '中国邮政储蓄银行';

    /**
     * @return array|mixed
     * @throws MatchException
     */
    public function dispatch()
    {
        $auth = strpos($this->message, '验证码');
        if ($auth !== false) {
            return $this->authCode();
        }
        $pos = strpos($this->message, '汇款金额');
        if ($pos !== false) {
            $data = $this->transfers();
        } else {
            $data = $this->pay();
        }
        return $data;
    }

    /**
     * 匹配提现
     * @return array|mixed
     * @throws MatchException
     */
    protected function transfers()
    {
        $pattern = '/(\d{3,4}).*向(.*)尾.*金额(\d+(\.\d{1,2})?)/';
        $result = preg_match($pattern, $this->message, $matches);
        if ($result !== 1) {
            throw new MatchException("Match failed");
        }
        return $this->backTr($matches[1], $this->units($matches[3]), $matches[2], $this->bankName, self::TRANSFERS);
    }

    /**
     * 匹配支付
     * @return array|mixed
     * @throws MatchException
     */
    protected function pay()
    {
        $pattern = '/(\d{3,4})\D+(\d+(\.\d{1,2})?)\D+(\d+(\.\d{1,2})?)/';
        $result = preg_match($pattern, $this->message, $matches);
        if ($result !== 1) {
            throw new MatchException("Match failed");
        }
        return $this->back($matches[1], $this->units($matches[2]), $this->bankName, $this->units($matches[4]), self::PAY);
    }

    /**
     * 匹配验证码
     * @return array|mixed
     */
    protected function authCode()
    {
        return $this->bankCode($this->bankName);
    }
}