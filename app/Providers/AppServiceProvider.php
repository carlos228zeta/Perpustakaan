<?php

namespace App\Providers;

use Illuminate\pagination\paginator;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // Auto-seed classes, majors, MLBB heroes, teachers, students, categories & books if missing
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && \Illuminate\Support\Facades\Schema::hasTable('students')) {
                $studentCount = \Illuminate\Support\Facades\DB::table('students')->count();
                $teacherCount = \Illuminate\Support\Facades\DB::table('teachers')->count();
                $bookCount = \Illuminate\Support\Facades\DB::table('books')->count();
                $classCount = \Illuminate\Support\Facades\DB::table('classes')->count();

                if ($studentCount < 10 || $teacherCount < 5 || $bookCount < 5 || $classCount < 30) {
                    (new \Database\Seeders\DatabaseSeeder())->run();
                }
            }
        } catch (\Throwable $e) {
            // Silently catch in early boot
        }
    }
}
