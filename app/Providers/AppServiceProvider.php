<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification;
use App\Services\CustomEmailService;
use App\Models\Answer;
use App\Observers\AnswerObserver;

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
        // Register Answer observer to update question status
        Answer::observe(AnswerObserver::class);

        // Add a custom Blade directive for safe output
        Blade::directive('sanitize', function ($expression) {
            return "<?php echo htmlspecialchars(strip_tags($expression), ENT_QUOTES, 'UTF-8'); ?>";
        });

        // Register custom notification channel
        Notification::extend('custom', function ($app) {
            return new class {
                public function send($notifiable, $notification)
                {
                    return $notification->toCustom($notifiable);
                }
            };
        });

        // Share storefront categories dynamically with the layout view
        view()->composer('polani.layout', function ($view) {
            $preferredSlugs = config('one_stop_mart.category_display_order', []);
            $allCategories = \App\Models\Category::where('is_storefront_category', true)->get();
            
            $sorted = $allCategories->sortBy(function ($category) use ($preferredSlugs) {
                $index = array_search($category->slug, $preferredSlugs);
                return $index === false ? 999999 : $index;
            })->values();

            $view->with('layoutCategories', $sorted);
        });
    }
}
