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
 * 平安银行
 * Class PINGAN
 * @package Mcy689\SmsFilter\Template
 */
class PINGAN extends BankBase
{
    protected $bankName = '平安银行';

    /**
     * 分析
     * @return array
     * @throws MatchException
     */
    public function dispatch(): array
    {
        $this->message = str_replace(',', '', $this->message);
        $pos = strpos($this->message, '转入');
        if ($pos !== false) {
            $data = $this->pay();
        } else {
            $data = $this->transfers();
        }
        return $data;
    }

    /**
     * 匹配提现
     * @return array
     * @throws MatchException
     */
    protected function transfers(): array
    {
        $pattern = '/(\d{4}).*人民币(\d+(\.\d{1,2})?).*收款人([\x{4e00}-\x{9fa5}]{2,4})/u';
        $result = preg_match($pattern, $this->message, $matches);
        if ($result !== 1) {
            throw new MatchException("Match failed");
        }
        return $this->backTr($matches[1], $this->units($matches[2]), $matches[4], $this->bankName, self::TRANSFERS);
    }

    /**
     * 匹配支付
     * @return array
     * @throws MatchException
     */
    protected function pay(): array
    {
        $pattern = '/(\d{4}).*转入\D+(\d+(\.\d{1,2})?)\D+(\d+(\.\d{1,2})?)/';
        $result = preg_match($pattern, $this->message, $matches);
        if ($result !== 1) {
            throw new MatchException("Match failed");
        }
        return $this->back($matches[1], $this->units($matches[2]), $this->bankName, $this->units($matches[4]), self::PAY);
    }
}