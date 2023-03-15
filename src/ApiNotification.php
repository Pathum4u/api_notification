<?php

namespace Pathum4u\ApiNotification;

use Illuminate\Broadcasting\Channel;
use Pathum4u\ApiRequest\ApiRequest;

class ApiNotification
{
    /**
     *
     */
    public $mail = [];

    /**
     *
     */
    public $database = [];

    /**
     *
     */
    public $chanel = [];

    /**
     *
     */
    public $sms = [];

    /**
     *
     */
    public $user;

    /**
     *
     */
    public $to = [];

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via()
    {
        return [
            //
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray()
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSms()
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification to.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toUser($users)
    {
        if (gettype($users) == 'object') {
            $users = $users->toArray();
        }

        if ($this->is_multi_array($users)) {
            $this->to = $users;
        } else {
            $this->to[] = $users;
        }
    }

    function is_multi_array($arr)
    {
        rsort($arr);
        return isset($arr[0]) && is_array($arr[0]);
    }

    /**
     *
     *
     */
    private function setData()
    {
        //
        $this->chanel = $this->via();
        $this->mail = $this->toMail();
        $this->database = $this->toArray();
        $this->sms = $this->toSms();

        return $this;
    }

    /**
     * Render the mail notification message into an HTML string.
     *
     * @return string
     */
    public function send($url, $service = 'notification')
    {
        //
        $this->setData();

        $chanel = new ApiRequest();
        $chanel->service($service);
        $chanel->url($url);
        $chanel->method('POST');
        $chanel->json($this);
        $chanel->debug(true);

        return $chanel->send();
    }
}
