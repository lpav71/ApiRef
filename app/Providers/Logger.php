<?php

namespace App\Providers;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class Logger extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Logger::class, function ($app) {
            return new Logger($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function create(string $data, string $description, string $tab_name, int $user_id) : int
    {
        $jsonData = $data;
        $log = new Log;
        $log->json_data = $jsonData;
        $log->date = Carbon::now();
        $log->description = $description;
        $log->tab_name = $tab_name;
        $log->user_id = $user_id;
        $res = $log->save();
        return $res ? 1 : 0;
    }
}
