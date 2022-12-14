<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OpinionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $inquiry)
    {
        $this->name = $name;
        $this->email = $email;
        $this->inquiry = $inquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->from($this->email)
            ->subject('テストタイトル')
            ->view('mail.mail')
            ->with([
                'name' => $this->name,
                'inquiry' => $this->inquiry,
            ]);
    }
}
