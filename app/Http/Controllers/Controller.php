<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendMail(){
        Mail::to("rashad.quantumitinnovation@gmail.com")->send(new TestMail('Rashad'));
        return 'Sent';
    }
}
