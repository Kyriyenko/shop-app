<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        if (app()->isProduction()) {
            DB::whenQueryingForLongerThan(500, function (Connection $connection) {
                dispatch(function () use ($connection) {
                    logger()
                        ->channel('telegram')
                        ->debug('whenQueryingForLongerThan:500' . $connection->totalQueryDuration());
                });
            });

            DB::listen(function ($query) {
                if ($query->time > 500) {
                    logger()
                        ->channel('telegram')
                        ->debug('whenQueryingForLongerThan:500' . $query->toSql());
                }
            });

            $app = app(Kernel::class);

            $app->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    DB::whenQueryingForLongerThan(500, function (Connection $connection) {
                        dispatch(function () {
                            logger()
                                ->channel('telegram')
                                ->debug('whenRequestLifecycleIsLongerThan 4 seconds' . request()->url());
                        });
                    });
                }
            );
        }
    }
}
