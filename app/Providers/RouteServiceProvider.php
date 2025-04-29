<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Регистрация маршрутов для приложения.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Регистрируем маршруты для API
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Загрузить маршруты для приложения.
     *
     * @return void
     */
    public function map()
    {
        // Если не хочешь регистрировать API в отдельном файле
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Если есть API маршруты
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }
}
