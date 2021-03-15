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
 * 光大银行
 * Class CEB
 * @package Lib\Pay\Lib\CoralAliPay\Template
 */
class CEB extends BankBase
{
    private $bankName = '光大银行';

    /**
     * 分析
     * @return array|mixed
     * @throws MatchException
     */
    public function dispatch()
    {
        $auth = strpos($this->message, '验证码');
        $auth2 = strpos($this->message, '动态密码');
        if ($auth !== false || $auth2 !== false) {
            return $this->authCode();
        }
        $pos = strpos($this->message, '入');
        if ($pos !== false) {
            $data = $this->pay();
        } else {
            $data = $this->transfers();
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
        $account = strpos($this->message, '向');
        if ($account === false) {
            $pattern = '/(\d{4}).*支出(\d+(\.\d{1,2})?)/';
            $result = preg_match($pattern, $this->message, $matches);
            if ($result !== 1) {
                throw new MatchException("Match failed");
            }
            return $this->backTr($matches[1], $this->units($matches[2]), '', $this->bankName, self::TRANSFERS);
        } else {
            $pattern = '/(\d{4}).*向(.*)转出(\d+(\.\d{1,2})?)/';
            $result = preg_match($pattern, $this->message, $matches);
            if ($result !== 1) {
                throw new MatchException("Match failed");
            }
            return $this->backTr($matches[1], $this->units($matches[3]), $matches[2], $this->bankName, self::TRANSFERS);
        }
    }

    /**
     * 匹配支付
     * @return array|mixed
     * @throws MatchException
     */
    protected function pay()
    {
        $pattern = '/(\d{4}).*[转存收]入(\d+(\.\d{1,2})?)\D+(\d+(\.\d{1,2})?)/';
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