<?php

namespace Ajtarragona\Reports;

use Ajtarragona\Reports\Commands\MakeReportCommand;
use Ajtarragona\Reports\Commands\PrepareJs;
use Ajtarragona\Reports\Services\ReportsService;
use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\Blade;
//use Illuminate\Support\Facades\Schema;

class ReportsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        //vistas
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tgn-reports');
        
        //cargo rutas
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        //idiomas
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'tgn-reports');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/ajtarragona-reports'),
        ], 'ajtarragona-reports-translations');


        //publico configuracion
        $config = __DIR__.'/Config/reports.php';
        
        $this->publishes([
            $config => config_path('reports.php'),
        ], 'ajtarragona-reports-config');


        $this->mergeConfigFrom($config, 'reports');


         //publico assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ajtarragona'),
        ], 'ajtarragona-reports-assets');

        $this->registerCommands();


        //cargo reports

        $reports=tgn_reports()->all();
        // dd($reports);
        try{
            foreach($reports as $report_name){
                $this->registerViews($report_name);
            }
        }catch(Exception $e){
            // dd($e);
        }

       
    }
    public function registerCommands()
    {
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeReportCommand::class,
                PrepareJs::class,
            ]);
        }
    }
    public function registerViews($report)
    {
        // dump($report);
        $report=tgn_reports()->find($report->short_name);
        // dd($report);
        if($report){
            $path = $report->getPath();
            // dump($path);
            if (is_dir($path)) {
                $this->loadViewsFrom($path, ReportsService::VIEWS_NAMESPACE.$report->short_name);
            }
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        //registro middleware
        $this->app['router']->aliasMiddleware('reports-backend', \Ajtarragona\Reports\Middlewares\ReportsBackend::class);

         //defino facades
        $this->app->bind('tgn-reports', function(){
            return new \Ajtarragona\Reports\Services\ReportsService;
        });
        
        


        //helpers
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }
    }
}
