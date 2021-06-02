<?php

namespace App\Providers;

use App\Models\Url;
use Gallib\ShortUrl\ShortUrl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        ShortUrl::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        $urls = Url::all();
        $expires_at_timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') + 3, date('y'));
        $current_timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('y'));
        foreach ($urls as $url) {
            if (strtotime($url->expires_at) > $expires_at_timestamp || strtotime($url->expires_at) < $current_timestamp) {
                $url->delete();
            }
        }
    }
}
