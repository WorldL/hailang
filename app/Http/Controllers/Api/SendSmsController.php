<?php

namespace App\Http\Controllers\Api;

use Dotenv\Validator;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mrgoon\AliSms\AliSms;
use App\Jobs\SendmsgQueue;
use App\Jobs\SendSms as SmsQueue;
use Illuminate\Support\Facades\Cache;


class SendSmsController extends Controller
{
    public function sendCode(Request $request)
    {
        
        if($request->isMethod('post')){
           $phone =  $request->input('phone');
           $smsid =  $request->input('smsid');
           //$smsid = 'SMS_158645111';
            //数据验证
            try{
                $request->validate([
                    'phone' => 'required|regex:/^1[3-8][0-9]{9}$/|string',
                    'smsid' => 'required'
                ],[
                    'phone.required' => '手机号必填',
                    'phone.regex' => '手机号不符合规范',
                    'phone.string' => '手机号为字符串格式',
                    'smsid.required' => '短信模板id必填'
                ]);
            }catch(\Illuminate\Validation\ValidationException $e){
                $err = $e->errors();
                return response()->json([
                    'code' => '0',
                    'message' => $err
                ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }

            $smsids = ['SMS_158645111'];

            if(in_array($smsid,$smsids)){

                $code = rand(10000,99999);
                //关联手机号缓存
                Cache::put('code_'.$phone,$code,1);
                //入队列发送短信(验证码)

                $this->dispatch(new SmsQueue($phone,$smsid,$param=['code'=>$code]));
                //通过短信的模板id来判断入哪个队列..然后来进行优先级区分
//                $job = (new SmsQueue($phone,$smsid,$param=['code'=>$code]))->onQueue('height');
//                $this->dispatch($job);

            }else{
                return response()->json([
                    'code' => '0',
                    'message' => '短信模板错误'
                ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }



            //$phone = '15964366508';
            //$phone = '18663582623';
            //$smsid = 'SMS_158645111';
            //$code = rand(10000,99999);
            //存缓存(关联手机号存储)


            //直接发短信
            // $sendmsg = new AliSms();
            // $sendmsg->sendSms($phone,$SMS_code,$param=['code'=>$code]);

            //入队
            // $this->dispatch(new SmsQueue($phone,$smsid,$param=['code'=>$code]));
            // $job = (new SmsQueue($phone,$smsid,$param=['code'=>$code]))->onQueue('height');
            // $this->dispatch($job);

        }


    }
}
