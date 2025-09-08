<?php

namespace App\Http\Controllers\Front;

use App\Data\Admin\Contacts\ContactSendData;
use App\Http\Controllers\Controller;
use App\Jobs\SendContactEmailJob;
use App\Mail\ContactMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        // Валидация всех полей, чтобы гарантировать их наличие
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        // Создаем объект данных из проверенного массива
        $data = new ContactSendData(
            email: $validated['email'],
            phone: $validated['phone'],
            message: $validated['message'],
        );

        // Отправляем job с валидными данными
        SendContactEmailJob::dispatch($data);

        //Mail::send(new ContactMail($data));

        return response()->json(['status' => 'ok']);
    }
}
