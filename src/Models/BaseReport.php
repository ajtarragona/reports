<?php

namespace Ajtarragona\Reports\Models;

use Ajtarragona\Reports\Services\ReportsService;
use Ajtarragona\Reports\Traits\ReportFormatters;
use Exception;
use PDF;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zip;
use ZipArchive;

class BaseReport
{
    
    use ReportFormatters;

    public $short_name = "";
    public $name = "";
    
    public $pagesize;
    public $orientation;
    public $margin;
    public $language;
    
    public $pagination=true;
    public $multiple = false;
    
   
    protected $entities=null;

    protected $template_name="template";
    protected $template_extension=".blade.php";
    
    
    protected $engine = "dompdf";

    protected $protected_tags = ["num_rows","table_body","columns","rows","column_key","column_label","column_value", "loop","row"];

    
    protected $config = [];
    protected $parameters = [];
    protected $columns = [];

    
    public function __construct()
    {
        $this->orientation = Arr::first($this->config('orientations',[config('reports.default_orientation')])) ;
        $this->pagesize = Arr::first($this->config('pagesizes',[config('reports.default_pagesize')])) ;
        $this->language = Arr::first($this->config('languages',[config('reports.default_language')])) ;
        $this->margin = $this->config('margin',config('reports.default_margin')) ;
        $this->pagination = $this->config('pagination',true) ;
        $this->multiple = $this->config('multiple',false) ;

    }


    public function name(){
        return $this->config('name', $this->name);
    }

    public function description(){
        return $this->config('description');
    }

    public function getBasePath(){
        return ReportsService::BASE_PATH;
    }


    public function getClassPath(){
        return ReportsService::reportClassPath($this->short_name);
    }
    public function getPath(){
        return ReportsService::reportPath($this->short_name);
    }

    protected function getReportClassName(){
        return ReportsService::reportClassName($this->short_name);
    }

    public function config($option=null, $default=null){
        if(!$this->config) $this->config = ReportsService::getConfigFile($this->short_name);
        if($option){
            return data_get($this->config, $option, $default);
        }else{
            return $this->config;
        }
    }
    
    private function getTemplateNameSuffixed($sufixes){
        $path=$this->getPath();
        
        foreach(array_permutations($sufixes) as $suf){
            $template_name = $this->template_name."-".strtolower(implode("-",$suf));
            // dump($path.DIRECTORY_SEPARATOR.$template_name.$this->template_extension);
            if(file_exists($path.DIRECTORY_SEPARATOR.$template_name.$this->template_extension)){
                return $template_name;
            }
        }
        return false;
    }

    
    public function templateName(){
        
       
        if($ret=$this->getTemplateNameSuffixed([
            $this->pagesize,
            $this->orientation,
            $this->language
        ])) return $ret;
        
     
        if($ret=$this->getTemplateNameSuffixed([
            $this->pagesize,
            $this->orientation,
        ])) return $ret;
     
        if($ret=$this->getTemplateNameSuffixed([
            $this->pagesize,
            $this->language,
        ])) return $ret;
        
        if($ret=$this->getTemplateNameSuffixed([
            $this->orientation,
            $this->language,
        ])) return $ret;
        
        
        if($ret=$this->getTemplateNameSuffixed([
            $this->pagesize,
        ])) return $ret;
        
      
        if($ret=$this->getTemplateNameSuffixed([
            $this->orientation,
        ])) return $ret;
        
        if($ret=$this->getTemplateNameSuffixed([
            $this->language,
        ])) return $ret;
  
        
        return $this->template_name;
    }

   
    public function getTemplatePath(){
       return $this->getPath().DIRECTORY_SEPARATOR.$this->templateName().$this->template_extension;
    }
    
    
    public function getAutodetectedTemplateParameters($paths=null){
        
            
       
        // dd($paths);
        $parameters=[];
        foreach($paths as $path){
            $template_parameters=[];
        
            if(file_exists($path)){
                $content=file_get_contents($path);
                // dump($content);
                preg_match_all('/\$[0-9a-zA-Z_]+/', $content, $matches);
                // dump($matches);
                if($matches && is_array($matches[0])){
                    $template_parameters=array_map(function($item){
                        return trim($item,'$');
                    }, $matches[0]);
                    // dump($template_parameters);
                    $template_parameters=array_diff($template_parameters, $this->protected_tags);
                    // dump($template_parameters);
                }
                // return $matches);

                if($template_parameters) $parameters=array_merge($parameters, $template_parameters);
            }
            
        }
        // dd($parameters);
       return array_unique($parameters);
       
        
        // dd($ret);
        // return $ret;
        

    }


    public function getCollectionParameterNames(){
        return collect($this->getParameters())->where('type','collection')->keys()->toArray();
    }
    public function getParameters(){
        $paths=[$this->getTemplatePath()];
        if($this->multiple){
            $paths=array_merge([
                $this->getPath().DIRECTORY_SEPARATOR.'footer'.$this->template_extension,
                $this->getPath().DIRECTORY_SEPARATOR.'header'.$this->template_extension,
            ], $paths );
        }
        $parameters=$this->getAutodetectedTemplateParameters($paths);
        
        $ret=$this->parameters ?? [];
        foreach($parameters as $key){
            if(!in_array($key, array_keys($this->parameters))){
                $ret[$key] = ["type"=>"text","label"=>$key ];
            }
        }
        return $ret;
    }

    public function applyFormatter($value, $formatter, $formatter_parameters=null){
        // dump($formatter, $value, $formatter_parameters);
        if(is_array($formatter)){
            $ret=$value;
            foreach($formatter as $i=>$format){
                $ret=$this->applyFormatter($ret, $format, $formatter_parameters[$i] ?? null);
            }
            return $ret;
        }else{
            $function_parameters = [$value];
            if($formatter_parameters) $function_parameters = array_merge([$value],$formatter_parameters);

            // dump($function_parameters, method_exists($this, $formatter));
            if(function_exists($formatter)) return $formatter(...$function_parameters);
            else if(method_exists($this, $formatter)) return $this->{$formatter}(...$function_parameters);
            return $value;
        }
    }


    protected function applyValue($value, $parameter){
        if(is_string($value)){
            if(Str::startsWith($value,"@")){
                $value=apply_value($value);
            }else{
                if(isset($parameter["formatter"])) $value=$this->applyFormatter($value, $parameter["formatter"], $parameter["formatter_parameters"]??[]);
            }
        }

        return $value;
    }
    public function prepareValue($value,  $parameter_name, $parameter = null){
        if(!is_null($value) && $parameter){
            return $this->applyValue($value, $parameter);
            
        }else{
            return "<code>".strtoupper($parameter_name)."</code>";
        }
    }
    /**
     * Inicializa los parametros que se le pasarán a la vista previa
     */
    public function prepareParameters($values=[]){
        
        
        if(isset($values["orientation"])) $this->orientation =  $values["orientation"];
        if(isset($values["pagesize"])) $this->pagesize =  $values["pagesize"];
        if(isset($values["language"])) $this->language =  $values["language"];
        if(isset($values["margin"])) $this->margin =  $values["margin"];
        if(isset($values["pagination"])) $this->pagination =  $values["pagination"];

           
        // dump($parameters);
        $parameters = $this->getParameters();
        
        // dd($parameters, $values);
        
        $ret=[];

        /** inicializo los parametros que no tengan valor con un tag */
        foreach($parameters as $parameter_name=>$parameter){
            if($parameter["type"]=="boolean"){
                $ret[$parameter_name] = ($values[$parameter_name]??null) ? true: false;
            }elseif($parameter["type"]=="collection"){
                $value=$values[$parameter_name]??null;
                // dump($value);
                if($value){
                    foreach($value as $i=>$row){
                        foreach($row as $key=>$col_value){
                            $value[$i][$key]=$this->prepareValue($col_value, $key, $parameter);
                        }
                    }
                    // dd($value);
                    $values[$parameter_name]=$value;
                }
                $ret[$parameter_name] = $value;
            }else{
                $value=$values[$parameter_name]??null;
                $value=$this->prepareValue($value,  $parameter_name, $parameter);
                $ret[$parameter_name] = $value;
                
            }
            
        }
        
        $ret['orientation']=$this->orientation;
        $ret['pagesize']=$this->pagesize;
        $ret['language'] = $this->language;
        $ret['margin'] = $this->margin;
        $ret['pagination'] = $this->pagination;
// dd($ret);
        return $ret;
    }



    /** 
     * Retorna si existeix una vista del mòdul
     */
    public function viewExists($viewname){
        $viewpath=$this->viewPath($viewname);
        // dump($viewpath);
            
        return view()->exists($viewpath);
    }

    /**
     * Retorna el path d'una vista del mòdul
     */
    public function viewPath($view_name){
        $namespace=ReportsService::VIEWS_NAMESPACE.$this->short_name;
        
        return $namespace."::".$view_name; //$this->module_type_name.".".$this->short_name."::".$view_name;
    }

    /**
     * Retorna una vista del mòdul
     */
    public function view($viewname, $attributes=[]){
        if($this->viewExists($viewname)){
            $args=[
                "report"=>$this
            ];
            $args=array_merge($args, $attributes);
            return view($this->viewPath($viewname), $args);
           
        }
    }

  
    


    public function getPagesizes(){
        return $this->config('pagesizes', []);
    }

    public function getPagesizesCombo(){
        $ret=[];
        foreach($this->getPagesizes() as $pagesize){
            $ret[$pagesize] = __('tgn-reports::reports.pagesizes.'.$pagesize);
        }
        return $ret;
    }

    

    public function getOrientations(){
        return $this->config('orientations', []);
    }

    public function getOrientationsCombo(){
        $ret=[];
        foreach($this->getOrientations() as $o){
            $ret[$o] = __('tgn-reports::reports.orientations.'.$o);
        }
        return $ret;
    }

    public function getLanguages(){
        return $this->config('languages', []);
    }

    public function getLanguagesCombo(){
        $ret=[];
        foreach($this->getLanguages() as $o){
            $ret[$o] = __('tgn-reports::reports.languages.'.$o);
        }
        return $ret;
    }







    protected function randomfilename(){
        return md5(microtime()).".pdf";
    }

    
    /** 
     * General el PDF
     * Streamea el archivo
     */
    public function stream($request=[], $rows=null){
        $pdf=$this->doGenerate($request, $rows);
        return $pdf->stream();
        
    }


     /** 
     * General el PDF
     * descarga el archivo
     */
    public function download($request=[], $rows=null){
        $pdf=$this->doGenerate($request, $rows);
        return $pdf->download();
    }


    /** 
     * General el PDF
     * Devuelve el contenido binario
     */
    public function generate($request=[], $rows=null){
        $pdf=$this->doGenerate($request, $rows);
        return $pdf->output();
    }

    
    public function saveTmp($request=[], $rows=null){
        $pdf=$this->generate($request, $rows);

        if($pdf){
            $now = $this->randomfilename();
            $path='tmp/reports/'.$now;
            $ret=Storage::put($path, $pdf);
            if($ret) return $path;

        }
        return null;

    }

    

    private function prepareMultipleBody(&$parameters, $rows){
        $ret="";

        
        $num_rows=count($rows);

        $columns=$this->getColumnsNameCombo();

        $parameters= array_merge($parameters, compact('columns','num_rows'));

        $ret.=$this->view('header', $parameters)->render();

        if($rows){
            
            foreach($rows as $i=>$row){
                 
                $values=$this->getColumnsValues($row);
                // dd($values);
                $args=array_merge($parameters,$values,[
                    'columns'=>$columns,
                    'row'=>$values,
                    'num_rows'=>$num_rows,
                    'loop'=> to_object([
                        "index"=>$i+1,
                        "index_0"=>$i,
                        "first"=>$i==0,
                        "last"=> ($i== (count($rows)-1))
                    ])
                ]);
                // dd($args);
                $ret.=$this->view('row', $args )->render();
               
                    
            }
        }

        $ret.=$this->view('footer', $parameters )->render();

        $parameters["table_body"] = $ret;
        
        // "<pre>".json_pretty($rows)."</pre>";
    }   

    
    /** 
     * General el PDF
     * Devuelve el contenido binario
     */
    private function doGenerate($parameters=[], $rows=[]){
 
        $parameters=$this->prepareParameters($parameters);
        // dd($rows);
        
        if($this->multiple) $this->prepareMultipleBody($parameters, $rows);
        
        // dd($parameters);
        // dd($template_name);
        PDF::setOptions(['isRemoteEnabled' => true]);

        try{
            
            return PDF::loadView( $this->viewPath($this->templateName()), $parameters)->setPaper($this->pagesize, $this->orientation);
        
        }catch(Exception $e){
            // dd($e);
        }
        
    }


    public function getColumnsValues($row){
        
        return collect($this->getColumns())->map(function($column, $key) use ($row){
        
            $ret= $this->applyValue($row[$key], $column);
            // dd($ret);
            return $ret;
        })->toArray();
    }
    public function getColumnsNameCombo(){
        // dd($report_columns);
        return array_map(function($column){
            return $column["label"];
        }, $this->getColumns());
    }

    public function getColumns(){
        if(uses_trait($this, 'Ajtarragona\Reports\Traits\MultipleReport')){

            $paths=[
                $this->getPath().DIRECTORY_SEPARATOR.'row'.$this->template_extension,
            ];
            
            $auto_columns= $this->getAutodetectedTemplateParameters($paths);
            // dd($auto_columns);
            $columns = $this->columns ?? [];

            // añado las columnas autodetectadas      
            foreach($auto_columns as $key){
                if(!in_array($key, array_keys($this->columns??[]))){
                    $columns[$key] = ["type"=>"text", "label"=>$key ];
                }
            }

             $report_params=array_keys($this->getParameters());
            
            //les añado type y label si no lo tienen            
            foreach($columns as $key=>$column){
                if(in_array($key, $report_params)){
                    //le quito los parametros generales del report
                    unset($columns[$key]);
                }else{
                    $columns[$key] = array_merge(["label"=>$key,'type'=>'text'], $column);
                }
            }
            
           
            return $columns;
        }
        return [];
    }


    
    /**
     * exporta zip con el fuente del report
     *
     * @return void
     */
    public function export(){
        
        $zip_file = $this->getReportClassName().'.zip'; // Name of our archive to download
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $path=$this->getPath();
        
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
        
                // extracting filename with substr/strlen
                $relativePath =   $this->getReportClassName() .''. DIRECTORY_SEPARATOR. substr($filePath, strlen($path) + 1);
        
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        
        return response()->download($zip_file);


    }

    
    

}
