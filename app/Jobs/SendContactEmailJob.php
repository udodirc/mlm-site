<?php

namespace App\Jobs;

use App\Data\Admin\Contacts\ContactSendData;
use App\Mail\ContactMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public ContactSendData $data;

    public function __construct(ContactSendData $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        Mail::send(new ContactMail($this->data));
    }
}
