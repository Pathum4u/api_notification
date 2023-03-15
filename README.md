API Request for Multiple source notification services
========================================

Simple Laravel\Lumen Micro service api notification request package(service to service).


Requirement    
------------

```
"pathum4u/api_request"
```

Installation 
------------

```
composer require pathum4u/api_notification
```

Request
-------


```
$notification = new RequisitionApprovalNotification($requisition_data);
$notification->toUser($this->get_users($permission, $requisition_data['requisition']['department_id']));
return $notification->send('/api/send_notification');
```


Notification
------------

```
<?php
namespace App\Notifications\Procurement;

use Pathum4u\ApiNotification\ApiNotification;
use Illuminate\Notifications\Messages\MailMessage;

class RequisitionApprovalNotification extends ApiNotification
{
    /**
     *
     *
     */
    public $data;

    public $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requisition_data)
    {
        //
        $this->data = $requisition_data;
        $this->user = $requisition_data['users'][0];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via()
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
            ->subject('New Requisition')
            ->line($this->user->name .' submitted new requisition for approval' )
            ->action('Show Requisition', url(env('FRONTEND_SERVICE_URI').'/requisition/conversation/'.$this->data['requisition']['slug']))
            ->line('Thank you for using our application!');
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
            'subject' => 'New Requisition',
            'action' => url(env('FRONTEND_SERVICE_URI') . '/requisition/conversation/' . $this->data['requisition']['slug']),
            'body' => $this->user->name . ' Submitted new requisition for approval',
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
}

```
For multiple service 

use service name from the config on pathumu4/api_request (config/services.php) package or by default it use 'notification'

```
->send('/send_notification', 'email_notification');
```

Other End (Notification Service)
---------

Create & Register Middleware on other end to validate each request with token. Use same key on both ends

```
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedSecrets = explode(',', env('MY_SECRETS_TOKEN'));

        if (in_array($request->header('Authorization'), $allowedSecrets)) {
            return $next($request);
        }

        // 
        return response()->json(['message' => 'unauthorized token'], 401);
    }
```

Create new Mail class

```
<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class MailNotification extends MailMessage
{
    /**
     *
     *
     */
    public  function push($data)
    {

        //
        foreach($data as $key => $value){
            $this->$key = $data->$key;
        }

        return $this;
    }
}
```

on controller get request

```
    /**
     * Notification Handler
     *
     *
     */
    public function sendNotification(Request $request)
    {
        //
        $data = json_decode($request->data);

        Notification::route('mail', $data->to)->notify(new SendNotification($data));

        return response()->json(['success'], 200);
    }
```

on your Notification use new MailNotification class

```
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //
        $mail = new MailNotification();
        $mail->push($this->data);
        return $mail;
    }
```

Acknowledgments
---------------

This project created specific requirements for one of my projects, this may not for everyone.


Worked & Tested 
-------

Laravel/Lumen


License
-------

Composer is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
