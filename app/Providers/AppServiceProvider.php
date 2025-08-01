<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Carbon::setLocale('id');
        App::setLocale('id');

        View::composer('layouts.navbars.auth.nav', function ($view) {
            $view->with('users', User::whereNull('google_id')->get());

            if (auth()->check()) {
                $unreadTotal = User::totalUnreadCount(auth()->id());
                $view->with('totalUnreadMessages', $unreadTotal);
            }
        });

        View::composer('pages.virtual-reality', function ($view) {
            $view->with([
                'totalUsers' => User::count(),
                'users' => User::limit(5)->get()
            ]);
        });
        

        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->role === {$role}): ?>";
        });
        
        Blade::directive('endrole', function () {
            return '<?php endif; ?>';
        });
        
    }
}
