<?php

namespace App\Integrations\Google;

use App\Models\DeviceToken;
use App\Models\User;
use Google\Exception;
use Google\Service\Exception as GoogleException;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
use Google_Client;
use Google_Service_FirebaseCloudMessaging;
use Google_Service_FirebaseCloudMessaging_Message;
use Google_Service_FirebaseCloudMessaging_Notification;

class GoogleService
{
    public function __construct(
        protected Google_Client $client,
    ) {}

    /**
     * @throws Exception
     */
    public function sendFirebaseNotification(User $user, string $title, string $message): void
    {
        $this->client->setAuthConfig(config('integrations.google-client.account-file'));
        $this->client->addScope(Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);

        $service = new Google_Service_FirebaseCloudMessaging($this->client);

        $user->getDeviceTokens()->map(function (DeviceToken $token) use ($title, $message, $user, $service) {
            $this->sendMessageToDevice($token, $title, $message, $service);
        });

    }

    protected function getFirebaseMessage(DeviceToken $token, string $title, string $messageText): Google_Service_FirebaseCloudMessaging_Message
    {
        $message = new Google_Service_FirebaseCloudMessaging_Message();
        $message->setToken($token->getToken());
        $message->setNotification(new Google_Service_FirebaseCloudMessaging_Notification([
            'title' => $title,
            'body' => $messageText,
        ]));

        return $message;
    }

    /**
     * @throws GoogleException
     */
    function sendMessageToDevice(DeviceToken $token, string $title, string $message, Google_Service_FirebaseCloudMessaging $service): void
    {
        $sendMessageRequest = new SendMessageRequest(['message' => $this->getFirebaseMessage($token, $title, $message)]);

        try {
            $service->projects_messages->send(
                'projects/' . config('integrations.google-client.app-id'),
                $sendMessageRequest
            );
        } catch (GoogleException $e) {
            if (in_array($e->getCode(), [404, 400])) {
                $token->delete();
            } else {
                throw $e;
            }
        }
    }
}
