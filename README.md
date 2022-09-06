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
use Pathum4u\ApiNotification\MailNotification;

$notification = new MailNotification();

return $notification->subject('Password reset Token')
    ->to($user) // or $users
    ->line('Use following key for reset user password.')
    ->line($user->token)
    ->line('Thank you for using our application!')
    ->send('/send_notification');
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
