<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Support\Laundry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('laundry', fn () => new Laundry);
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);

        // Share laundry config ke semua views
        View::share('laundry', app('laundry'));

        // Blade directive: @laundryName, @laundryPhone dll
        Blade::directive('laundryName', fn () => '<?php echo e(\\App\\Support\\Laundry::name()); ?>');
        Blade::directive('laundryAddress', fn () => '<?php echo e(\\App\\Support\\Laundry::address()); ?>');
        Blade::directive('laundryPhone', fn () => '<?php echo e(\\App\\Support\\Laundry::phoneDisplay()); ?>');
    }
}
