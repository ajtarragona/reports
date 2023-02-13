<?php

namespace Ajtarragona\Reports\Commands;

use Illuminate\Console\Command;

use \Artisan;
use Illuminate\Support\Facades\File;  

class PrepareJs extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ajtarragona:reports:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare Javascript and CSS resources';


    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
   
        $this->line("Publishing assets ...");
        Artisan::call('vendor:publish',['--tag'=>'ajtarragona-reports-assets','--force'=>true]);

        
    }



}
