<?php

namespace App\Mail;

use App\Data\Admin\Contacts\ContactSendData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSendData $data) {}

    public function build()
    {
        return $this->subject('Новое сообщение с формы контактов')
            ->view('emails.contact')
            ->with(['dto' => $this->data]);
    }
}
