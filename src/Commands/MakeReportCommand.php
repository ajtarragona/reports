<?php

namespace Ajtarragona\Reports\Commands;

use Ajtarragona\Reports\Services\ReportsService;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

class MakeReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tgn-report {name : The report name} {--m|multiple : Multiple report } {--f|force : Force creation } ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Report';
    protected $namespace = 'Reports';

    protected $name;
    protected $multiple=false;
    protected $files;
    protected $options = [];
    
    protected $stubs_base_path = "stubs";
    protected $stub_templates = ['default'];


    protected function buildReplacements($replacements=[]){
        return array_merge([
            '_REPORT_NAME_' => $this->reportName(),
            '_REPORT_SLUG_' => $this->name,
            '_REPORT_MULTIPLE_' => $this->multiple ? 'true': 'false',
            '_REPORT_CLASS_NAME_' => $this->reportClassName()
        ], $replacements);
    }

    public function __construct(Filesystem $files)
    {
       
        parent::__construct();
        $this->files = $files;
        
    }
   

    public function handle()
    {
        $this->info('Running TGN Report Generator ...');
        $this->multiple = $this->option('multiple') ? true:false;
            
        $this->name = Str::snake(str_replace('-','_',trim($this->argument('name'))));
        
        $ret=$this->makeReportFolder();
        if($ret){
            $this->makeReportConfig()
            ->makeReportClass()
            ->makeReportTemplate()
            ;

            $this->info('Created Successfully.');

        }

        
        return true;
    }

   
    protected function restoreReportFolder(){
        $this->info("Deleting Folder: `{$this->reportPath()}` ...");
        $this->files->deleteDirectory( base_path($this->reportPath()));
        $this->doMakeReportFolder();
        return $this;
    }


    protected function doMakeReportFolder(){
        $this->info("Creating Folder: `{$this->reportPath()}` ...");
        $this->files->makeDirectory( base_path($this->reportPath()) , 0777, true, true);
        return $this;
    }

    protected function makeReportFolder()
    {
       if ($this->reportExists()) {
            // $this->error("`{$this->name}` report already exists in folder `{$this->reportPath()}`");
            if($this->ask("`{$this->name}` report already exists in folder `{$this->reportPath()}`. Do you want to overwrite (y/n)?", 'y') == 'y'){
                // $this->restoreReportFolder();
            }else{
                return false;
            }
        }else{
            $this->doMakeReportFolder();
        }
        
        return $this;
    }

    protected function makeReportConfig(){
        $file_path=$this->reportPath('config.php');
        $exists=$this->files->exists(base_path($file_path));
        // dd($exists);
        if(!$exists || ($exists && $this->ask('Config file already exists. Do you want to overwrite (y/n)?', 'y') == 'y')){
            $this->makeFile("config", "config.php");
        
            $this->info('Created `config.php`');
        }
        
        return $this;
    }


    protected function makeReportClass(){
        $classname=$this->reportClassName().".php";

        $file_path=$this->reportPath($classname);
        $exists=$this->files->exists(base_path($file_path));
        // dd($exists);
        if(!$exists || ($exists && $this->ask('Report class file already exists. Do you want to overwrite (y/n)?', 'y') == 'y')){
            $this->makeFile("Report".($this->multiple?"Multi":""), $classname);
        
            $this->info("Created `{$classname}`");
        }
        
        return $this;
    }

    protected function generateTemplateFromStub($template_name, $target_name=null){
        if(!$target_name) $target_name=$template_name;

        $file_path=$this->reportPath($target_name.'.blade.php');
        $exists=$this->files->exists(base_path($file_path));
        // dd($exists);
        if(!$exists || ($exists && $this->ask('Template `'.$target_name.'` file already exists. Do you want to overwrite (y/n)?', 'y') == 'y')){
            $this->makeFile($template_name.".blade", $target_name.".blade.php");
        
            $this->info('Created `'.$target_name.'.blade.php`');
        }
    }

    protected function makeReportTemplate(){
        if($this->multiple){
            foreach(['footer','header','row'] as $template_name){
                $this->generateTemplateFromStub($template_name);
            }
            $this->generateTemplateFromStub('template-multi','template');
        }else{
            $this->generateTemplateFromStub('template');
        }
        
        return $this;
    }

    /**
     * makes a file from a stub
     *
     * @param  mixed $stub_name
     * @param  mixed $file_name
     * @return void
     */
    protected function makeFile($stub_name, $file_name){
        
        $content = $this->getStub($stub_name);
        $replacements= $this->buildReplacements();

        $content = str_replace( array_keys($replacements), array_values($replacements), $content);

        $file_path=$this->reportPath($file_name);
        if($this->files->exists(base_path($file_path))){
            $this->files->delete($file_path);
        }
        
        $this->files->put( base_path($file_path) , $content);
        
       
        
    }



    /**
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     */
    protected function buildModel()
    {

        $modelPath = $this->_getModelPath($this->name);
        // dd($this->name);
        if ($this->files->exists($modelPath) && $this->ask('Already exist Model. Do you want overwrite (y/n)?', 'y') == 'n') {
            return $this;
        }

        // $this->info('Creating Model ...');
        // Artisan::call('code:models',['--table'=> $this->table]);

        // Make the models attributes and replacement
        $replace = array_merge($this->buildReplacements(), $this->modelReplacements());

        $modelTemplate = str_replace(
            array_keys($replace), array_values($replace), $this->getStub('Model')
        );

        $this->write($modelPath, $modelTemplate);

        return $this;
    }

    /**
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @throws \Exception
     */
    protected function buildViews()
    {
        $this->info('Creating Views ...');

        $tableHead = "\n";
        $tableBody = "\n";
        $viewRows = "\n";
        $form = "\n";

        foreach ($this->getFilteredColumns() as $column) {
            $title = Str::title(str_replace('_', ' ', $column));

            $tableHead .= $this->getHead($title);
            $tableBody .= $this->getBody($column);
            $viewRows .= $this->getField($title, $column, 'view-field');
            $form .= $this->getField($title, $column, 'form-field');
        }

        $replace = array_merge($this->buildReplacements(), [
            '{{tableHeader}}' => $tableHead,
            '{{tableBody}}' => $tableBody,
            '{{viewRows}}' => $viewRows,
            '{{form}}' => $form,
        ]);

        $this->buildLayout();

        foreach (['index', 'create', 'edit', 'form', 'show'] as $view) {
            $viewTemplate = str_replace(
                array_keys($replace), array_values($replace), $this->getStub("views/{$view}")
            );

            $this->write($this->_getViewPath($view), $viewTemplate);
        }

        return $this;
    }

   
    

    protected function folderName(){
        return Str::studly($this->name)."Report";

    }
    protected function reportClassName(){
        return $this->folderName();

    }

    protected function reportName(){
        return Str::title(str_replace(['_','-'], ' ', $this->name)); //viene de snake case o kebab case
    }

    protected function reportPath($name=null){
        return ReportsService::storagePath() . DIRECTORY_SEPARATOR. $this->folderName() . ($name?  (DIRECTORY_SEPARATOR.$name) : '' );
    }


    protected function reportExists()
    {   
        return $this->files->exists( base_path($this->reportPath()) );
        

    }


    protected function getStubPath($name){
        $stub_path = config('reports.stub_path', 'default');

        if (in_array($stub_path, $this->stub_templates)){
            $stub_path = __DIR__ . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$this->stubs_base_path.DIRECTORY_SEPARATOR.$stub_path;
        }

        return  Str::finish($stub_path, DIRECTORY_SEPARATOR) . "{$name}.stub";

       
        // return base_path($this->stubs_base_path.DIRECTORY_SEPARATOR.$name.".stub");

    }

    protected function getStub($name, $content = true)
    {
        $stub_path =  $this->getStubPath($name);
        // dd($stub_path);
        if (!$content) {
            return $stub_path;
        }

        return $this->files->get($stub_path);
    }
    
}
