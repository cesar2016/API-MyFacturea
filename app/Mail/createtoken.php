<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class createtoken extends Mailable
{
    use Queueable, SerializesModels;
 
    public $subject = "API TOKEN creado con exito!";
     

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_user = auth()->user()->email;
        $name_user = auth()->user()->name;

        // Aca Elimino todos los tokens del usuario
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });

         // Aca creo un nuevo tokenpara este user
        $user = User::where('email', $mail_user)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        /* $new_token = response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer' 
        ]); */        

        return $this->view('emails.mail_token', [
            'name_user' => $name_user,
            'token' => $token
        ]);
    }
}
