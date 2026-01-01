<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;

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
        // Chỉ load categories khi bảng đã tồn tại
        try {
            if (\Schema::hasTable('categories')) {
                $menuCategories = Category::with(['children' => function ($q) {
                        $q->where('is_active', true)->orderBy('name');
                    }])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();

                view()->share('menuCategories', $menuCategories);
            } else {
                view()->share('menuCategories', collect());
            }
        } catch (\Exception $e) {
            view()->share('menuCategories', collect());
        }
    }
}
