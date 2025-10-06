<?php

namespace App\Enums;

enum SettingsEnum: string
{
    case SendMessage = 'send_message';
    case AdminEmail = 'admin_email';

    public function label(): string
    {
        return match ($this) {
            self::SendMessage => 'Включить форму связи',
            self::AdminEmail => 'Email администратора',
        };
    }

    public function defaultValue(): mixed
    {
        return match ($this) {
            self::SendMessage => true,
            self::AdminEmail => 'admin@example.com',
        };
    }
}
