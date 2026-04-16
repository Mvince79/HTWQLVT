<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Trip;
use App\Observers\TripObserver;
use App\Models\Vehicle;
use App\Observers\VehicleObserver;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Trip::observe(TripObserver::class);
        Vehicle::observe(VehicleObserver::class);

        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::BODY_END,
        //     fn() => view('filament.echo-script'),
        // );

    }
}
