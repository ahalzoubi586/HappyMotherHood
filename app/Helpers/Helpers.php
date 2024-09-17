<?php

namespace App\Helpers;

use App\Models\User;
use Exception;
use Google\Client;
use Illuminate\Support\Facades\Log;

class Helpers
{

    static function send_to_topic($data)
    {
        Log::info($data);
        $accessToken = self::getAccessToken();
        $url = 'https://fcm.googleapis.com/v1/projects/jamad-nabat/messages:send';
        $fields = [
            'topic'        => $data['topic'],
            'notification' => [
                'title' => $data['title'],
                'body'  => $data['body'],
            ],
            'data'         => ['type' => $data['type']]
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $fields]));
        $result = curl_exec($ch);

        // Check for errors
        $result = curl_exec($ch);
        if ($result === FALSE) {
            Log::error('Curl failed: ' . curl_error($ch));
        } else {
            $response = json_decode($result, true);
            Log::info($response);
            Log::info('FCM Notification sent successfully.');
        }
        curl_close($ch);
        return $result;
    }
    static function send_to_user($userToken, $data)
    {
        $accessToken = self::getAccessToken();
        //$url = 'https://fcm.googleapis.com/fcm/send';
        $url = "https://fcm.googleapis.com/v1/projects/jamad-nabat/messages:send";
        $message = [
            'token' => $userToken,
            'notification' => [
                'title' =>  $data['title'],
                'body' =>  $data['body'],
            ],
            'data' => [
                'type' => $data['type'],
                'conversation_id' => $data['conversation_id'],
                'username' => $data['username']
            ],
        ];
        Log::info($message);
        // HTTP headers with Authorization key
        $headers = array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        );

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));

        // Execute cURL request
        $result = curl_exec($ch);

        // Check for errors
        $result = curl_exec($ch);
        if ($result === FALSE) {
            Log::error('Curl failed: ' . curl_error($ch));
        } else {
            $response = json_decode($result, true);
            if (isset($response['error']) && $response['error']['status'] === 'NOT_FOUND') {
                Log::error("Invalid FCM token: {$userToken}. Removing from database.");
                self::removeInvalidToken($userToken);
            } else {
                Log::info('FCM Notification sent successfully.');
            }
        }

        // Close cURL session
        curl_close($ch);

        return $result;
    }
    static function getAccessToken()
    {
        $path = base_path("firebase_cred.json");
        $client = new Client();
        $client->setAuthConfig($path);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
    private static function removeInvalidToken($token)
    {
        User::where('firebase_token', $token)->update(['firebase_token' => null]);
    }
}
