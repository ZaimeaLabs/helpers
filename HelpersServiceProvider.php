<?php

declare(strict_types=1);

namespace ZaimeaLabs\Helpers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Blade::directive('todo', function () {
            return "<?php if(!app()->environment('production')): ?>";
        });
        Blade::directive('endtodo', function () {
            return "'<?php endif; ?>';";
        });
    }
}
