<?php

namespace App\Http\Controllers\Front;

use App\Data\Admin\Contacts\ContactSendData;
use App\Http\Controllers\Controller;
use App\Jobs\SendContactEmailJob;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function send(ContactSendData $data): JsonResponse
    {
        // Отправляем job в RabbitMQ
        SendContactEmailJob::dispatch($data);

        return response()->json(['status' => 'ok']);
    }
}
