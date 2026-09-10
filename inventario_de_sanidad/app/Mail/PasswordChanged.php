<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChanged extends Mailable {
    use Queueable, SerializesModels;

    protected $password;
    protected $first_name;
    protected $last_name;

    public function __construct($password, $first_name, $last_name) {
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    public function build() {
        return $this->subject('🔐 Restablecimiento de contraseña')->view('emails.password-changed')
            ->with([
                'password' => $this->password,
                'first_name' => $this->first_name,
                'last_name' =>$this->last_name 
            ]);
    }
}
