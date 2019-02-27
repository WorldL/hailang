<?php

namespace App\Service;

use Mrgoon\AliSms\AliSms;

class SendSms
{
    private $aliSms=null;

    /**
     * SendSms constructor.
     * @param string $phone
     * @param string $code
     * @param array $param
     */
    public function __construct()
    {

        $this->aliSms = new AliSms();


    }

    /**
     * @return mixed|\SimpleXMLElement
     */
    public function sendmsg($phone,$code,$param)
    {
        return $this->aliSms->sendSms($phone,$code,$param);

    }

}
