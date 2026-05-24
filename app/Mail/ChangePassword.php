<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangePassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $password,$first_name, $last_name )
    {
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name ;    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🔐 Restablecimiento de Contraseña')->view('emails.changedPassword')
                    ->with([
                        'password' => $this->password,
                        'first_name' => $this->first_name,
                        'last_name' =>$this->last_name 
                    ]);
    }
}
