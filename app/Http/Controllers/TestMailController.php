<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function index() {
        $mailData = [
            'title' => 'This is Test Mail',
            'body' => 'Test mail from finfeedapp in laravel 11 using gmail.'
        ];
           
        Mail::to('carlmabfredishere@gmail.com')->send(new TestMail($mailData));
             
        dd("Email is sent successfully.");
    }
}
