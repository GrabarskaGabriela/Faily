<?php

/**
 * Nie wiem jak to zarejstrować :(
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ClearViewCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cache::flush();
        $this->info('App cache has been cleared');

        Artisan::call('view:clear');
        $this->info('View cache has been cleared');

        Artisan::call('view:clear');
        $this->info('View cache has been cleared');

        Artisan::call('route:clear');
        $this->info('Route cache has been cleared');

        Artisan::call('config:clear');
        $this->info('Config cache has been cleared');

        Artisan::call('optimize:clear');
        $this->info('Optimized cache has been cleared');

        $availableLocales = ['pl', 'en', 'jpn', 'es', 'ua'];

        foreach ($availableLocales as $locale) {
            Cache::forget('translations_{$locale}');
            $this->info("Cache cleared for locale {$locale}");
        }

        foreach ($availableLocales as $locale) {
            $keys = Cache::get('navbar_keys', []);
            foreach ($keys as $key) {
                if (strpos($key, "navbar_{$locale}_") === 0) {
                    Cache::forget($key);
                    $this->info("Cleared cache for {$key}");
                }
            }

            Cache::forget("footer_{$locale}");
            $this->info("Cleared cache for footer_{$locale}");
        }

        $this->info("Cache cleared");
        return COMMAND::SUCCESS;
    }
}
