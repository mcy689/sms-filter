<?php
/*
 * This file is part of the mcy689/smsFilter.
 *
 * (Author) mcy689 <nideshijian@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Mcy689\SmsFilter;

use Mcy689\SmsFilter\Exceptions\LoadException;
use Mcy689\SmsFilter\Exceptions\MatchException;

class Sms implements SmsFilterInterface
{
    const COMMON = [
        '95588' => 'ICBC',
        '95555' => 'CMB',
        '95533' => 'CCB',
        '95599' => 'ABC',
        '95511' => 'PINGAN',
        '95580' => 'PSBC',
        '95561' => 'PBOC',
        '95595' => 'CEB',
        '10086' => 'Operator',
        '10010' => 'Operator',
    ];
    const BANK_LIST = [
        'ICBC' => '工商',
        'CMB' => '招商',
        'CCB' => '建设',
        'ABC' => '农业',
        'PINGAN' => '平安',
        'PSBC' => '邮政',
        'PBOC' => '兴业',
        'CEB' => '光大',
    ];

    /**
     * 根据银行编号，分析短信
     * @param $number
     * @param $message
     * @return mixed
     * @throws LoadException
     * @throws MatchException
     */
    public static function analyse($number, $message)
    {
        $number = trim($number);
        if (is_numeric($number)) {
            foreach (self::COMMON as $key => $item) {
                $pos = strpos($number, (string)$key);
                if ($pos !== false) {
                    $className = $item;
                    break;
                }
            }
        } else {
            foreach (self::BANK_LIST as $key => $item) {
                $pos = strpos($number, $item);
                if ($pos !== false) {
                    $className = $key;
                    break;
                }
            }
        }
        if (!isset($className)) {
            throw new MatchException(sprintf('No matching bank class : %s', $number));
        }
        $className = __NAMESPACE__ . '\\Template\\' . $className;
        if (!class_exists($className)) {
            throw new LoadException(sprintf('Load Fail bank class : %s', $className));
        }
        $message = str_replace(',', '', $message);
        $analyzeM = new $className($message);
        return $analyzeM->dispatch();
    }
}
