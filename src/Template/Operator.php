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

/**
 * 运营商短信
 * Class Operator
 * @package Mcy689\SmsFilter\Template
 */
class Operator extends BankBase
{
    public function dispatch()
    {
        return $this->back(0, 0, '', 0, self::OPERATOR);
    }

    protected function transfers()
    {
    }

    protected function pay()
    {
    }

    protected function authCode()
    {
    }
}