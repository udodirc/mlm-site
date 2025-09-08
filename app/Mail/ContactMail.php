<?php

namespace App\Mail;

use App\Data\Admin\Contacts\ContactSendData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public ContactSendData $data;

    public function __construct(ContactSendData $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from($this->data->email, 'Contacts')
            ->to(config('mail.admin_address'))
            ->subject('Новое сообщение с формы контактов')
            ->view('emails.contact')
            ->with([
                'data' => $this->data
            ]);
    }
}
