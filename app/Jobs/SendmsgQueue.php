<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mrgoon\AliSms\AliSms;

class SendmsgQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $SMS_code;
    public $param;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone,$SMS_code,$param=['code'=>1234])
    {
        $this->phone = $phone;
        $this->SMS_code = $SMS_code;
        $this->param = $param;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendmsg = new AliSms();
        $sendmsg->sendSms($this->phone,$this->SMS_code,$this->param);
    }
}
