<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Transporter;
use Exception;
use Log;
use Setting;
use App\Notification;
use PushNotification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class SendPushNotification extends Controller
{
	

    /**
     * Money added to user wallet.
     *
     * @return void
     */
    public function WalletMoney($user_id, $money){

        return $this->sendPushToUser($user_id, $money.' '.trans('form.push.added_money_to_wallet'));
    }

    /**
     * Money charged from user wallet.
     *
     * @return void
     */
    public function ChargedWalletMoney($user_id, $money){

        return $this->sendPushToUser($user_id, $money.' '.trans('form.push.charged_from_wallet'));
    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToUser($user_id, $push_message){

    	try{

	    	$user = User::findOrFail($user_id);
                Notification::create([
                    'user_id' => $user_id,
                    'message' => $push_message
                ]);
            if($user->device_token != ""){

                \Log::info('sending push for user : '. $user->name);
    	    	if($user->device_type == 'ios'){

    	    		return PushNotification::app('IOSUser')
    		            ->to($user->device_token)
    		            ->send($push_message);

    	    	}elseif($user->device_type == 'android'){
    	    		
    	    		$optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder((env("APP_NAME", "dragon")));
                    $notificationBuilder->setBody($push_message)
                        ->setSound('default');

                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['a_data' => 'my_data']);

                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();

                    $token = $user->device_token;

                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                    Log::info("fcm run");
                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();


                    return \PushNotification::app('AndroidUser')
                        ->to($user->device_token)
                        ->send($push_message);

    	    	}
            }

    	} catch(Exception $e){
    		return $e;
    	}

    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToProvider($provider_id, $push_message){

    	try{

	    	$provider = Transporter::findOrFail($provider_id);
                Notification::create([
                    'transporter_id' => $provider_id,
                    'message' => $push_message
                ]);
            if($provider->device_token != ""){
                
                \Log::info('sending push for provider : '. $provider->name);

            	if($provider->device_type == 'ios'){

            		return \PushNotification::app('IOSProvider')
        	            ->to($provider->device_token)
        	            ->send($push_message);

            	}elseif($provider->device_type == 'android'){
            		
            		$optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder(env("APP_NAME", "dragon")." Delivery");
                    $notificationBuilder->setBody($push_message)
                        ->setSound('default');

                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['a_data' => 'my_data']);

                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();

                    $token = $provider->token;

                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                    Log::info("fcm run");
                    Log::info($downstreamResponse);
                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();

                    return \PushNotification::app('AndroidProvider')
                        ->to($provider->token)
                        ->send($push_message);

            	}
            }

    	} catch(Exception $e){
    		return $e;
    	}

    }

}
