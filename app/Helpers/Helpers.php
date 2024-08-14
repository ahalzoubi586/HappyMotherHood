<?php

namespace App\Helpers;

class Helpers
{

    static function send_to_topic($message, $topic)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to'           => "/topics/$topic",
            'priority'     => "high",
            'notification' => $message,
            'data'         => $message
        );

        //var_dump($fields);

        $headers = array(
            'Authorization:key = AAAAMPOLmCQ:APA91bEO1JWsehx-stmyF7WLJs8q-zLpUlJ1ygQuz9ShYxuDal-OVg_DrdeuFnELwqox408SsH3qddLaPRW7C40OcHf_mifuBU8gGH-M22FkShXxrD_4WNYdN4dQRd0tFJwUeWQw3BvA',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}