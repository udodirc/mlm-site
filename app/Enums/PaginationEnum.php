<?php
namespace App\Enums;

enum PaginationEnum: string
{
    case User = 'per_page_users';
    case Role = 'per_page_roles';
    case Menu = 'per_page_menus';
    case Content = 'per_page_content';
    case StaticContent = 'per_page_static_content';
    case Project = 'per_page_project';

    public function label(): string
    {
        return match($this) {
            self::User => 'Количество пользователей на странице',
            self::Role => 'Количество ролей на странице',
            self::Menu => 'Количество меню на странице',
            self::Content => 'Количество контента на странице',
            self::StaticContent => 'Количество статического контента на странице',
            self::Project => 'Количество проектов на странице',
        };
    }
}

