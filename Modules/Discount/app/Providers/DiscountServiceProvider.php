<?php

namespace Modules\Discount\Providers;

use Illuminate\Support\Facades\View;
use Modules\Book\Models\Book;
use Modules\Book\Models\Category;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class DiscountServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Discount';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'discount';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     *
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
    public function boot():void
    {
        $this->loadViewsFrom(
            module_path('Discount', 'Resources/views'),
            'discount'
        );
        View::composer([
            'discount::admin.create',
            'discount::admin.edit'
        ], function ($view){
            $view->with([
              'books' => Book::all(),
              'categories' => Category::all(),
            ]);
        });
    }
}
