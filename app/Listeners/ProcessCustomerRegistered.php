<?php

namespace App\Listeners;

use App\Events\CustomerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class ProcessCustomerRegistered implements ShouldQueue
{
  public $queue = 'emails';

  public function __construct() {}

  public function handle(CustomerRegistered $event)
  {

    \Debugbar::info($event->customer);

    //send mail
    Mail::to($event->customer->email)->send(new WelcomeMail($event->customer));
  }
}
