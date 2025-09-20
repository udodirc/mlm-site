<?php

namespace App\Mail;

use App\Data\Admin\Contacts\ContactSendData;
use App\Services\SettingsManager;
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
        $settings = SettingsManager::load();
        $adminEmail = $settings['admin_email'] ?? config('mail.from.address');

        return $this->from($this->data->email, 'Contacts')
            ->to($adminEmail)
            ->subject('Новое сообщение с формы контактов')
            ->view('emails.contact')
            ->with([
                'data' => $this->data
            ]);
    }
}
