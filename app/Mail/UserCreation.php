<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $password,$first_name, $last_name,$email )
    {
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name ;
        $this->email = $email ;   

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Usuario creado en el portal de Sanidad')->view('emails.userCreation')
                    ->with([
                        'password' => $this->password,
                        'first_name' => $this->first_name,
                        'last_name' =>$this->last_name ,
                        'email' => $this->email
                    ]);
    }
}
