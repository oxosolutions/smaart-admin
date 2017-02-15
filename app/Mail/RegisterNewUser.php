<?php

namespace App\Mail;
use App\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterNewUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $userDet;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userDet)
    {
        $this->userDet = $userDet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Thanks For Register with us!';
        return $this->view('mail.register',
                                            [
                                                'user'=>$this->userDet
                                            ]
                          )->subject($subject);
    }
}
