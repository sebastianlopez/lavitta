<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendErrors($errors,$email='soporte@rhiss.net'){

        try {
            Mail::send('email.errors', $errors, function ($message) use ($email) {

                $message->subject(trans('Error Importing') );
                $message->from('noreply@lavita.com');
                $message->to($email);

            });
        } catch (\Exception $e) {
            $info = ['function' => 'email Errors', 'msg' => $e->getMessage()];
            Log::info(json_encode($info));
        }
    }

}
