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
use Illuminate\Filesystem\Filesystem;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zip;
use ZipArchive;
use Imagick;

class BaseReport
{
    
    use ReportFormatters;

    public $short_name = "";
    public $name = "";
    public $preview_mode = false;
    
    public $pagesize;
    public $orientation;
    public $margin;
    public $language;
    
    public $pagination=true;
    public $multiple = false;
    
   
    protected $entities=null;

    protected $template_name="template";
    protected $template_extension=".blade.php";
    protected $template_attributes=[];
    
    
    protected $engine = "dompdf";

    protected $protected_tags = ["num_rows","table_body","columns","rows","column_key","column_label","column_value", "loop","row"];
    protected  $autodetect_parameters=true;
    protected  $excluded_parameters=[];
    
    protected $config = [];
    protected $parameters = [];
    protected $columns = [];
    protected $rows = [];

    
    public function __construct()
    {
        $this->orientation = Arr::first($this->config('orientations',[config('reports.default_orientation')])) ;
        $this->pagesize = Arr::first($this->config('pagesizes',[config('reports.default_pagesize')])) ;
        $this->language = Arr::first($this->config('languages',[config('reports.default_language')])) ;
        $this->margin = $this->config('margin',config('reports.default_margin')) ;
        $this->pagination = $this->config('pagination',true) ;
        $this->multiple = $this->config('multiple',false) ;

    }

public function isMultiple(){
    return $this->multiple;
}
    public function name(){
        return $this->config('name', $this->name);
    }
    public function icon(){
        return $this->config('icon', null);
    }

    public function description(){
        return $this->config('description');
    }

    public function getBasePath(){
        return ReportsService::storagePath();
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
        
        if(!$this->autodetect_parameters) return [];    
       
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
                    $template_parameters=array_diff($template_parameters, array_merge($this->protected_tags, $this->excluded_parameters));
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


    public function getFunctionParameters(){
        $ret=collect($this->parameters)->filter(function($parameter){
            return isset($parameter["function"]);
        })->toArray();
        return $ret;
    }

    public function getCollectionFunctionParameters($param_name){
        
        $ret=[];
        if(isset($this->parameters[$param_name]) && $this->parameters[$param_name]["type"]=="collection" && $this->parameters[$param_name]["columns"]){
            $ret=collect($this->parameters[$param_name]["columns"])->filter(function($parameter){
                return isset($parameter["function"]);
            })->toArray();
        }
        return $ret;
    }


    public function getParameters($exclude_functions=true){
        $paths=[$this->getTemplatePath()];
        if($this->multiple){
            $paths=array_merge([
                $this->getPath().DIRECTORY_SEPARATOR.'footer'.$this->template_extension,
                $this->getPath().DIRECTORY_SEPARATOR.'header'.$this->template_extension,
            ], $paths );
        }

        
        $ret=$this->parameters ?? [];

        $ret=collect($ret);
        
        if($exclude_functions){
            $ret=$ret->filter(function($parameter){
                //quito lo parametros function
                return !isset($parameter["function"]);
            })->map(function($parameter){
                //quito lo parametros function de las colecciones
                if($parameter["type"]=="collection"){
                    $parameter["columns"]=collect($parameter["columns"])->filter(function($parameter){
                        return !isset($parameter["function"]);
                    })->toArray();
                }
                return $parameter;
            });
        }

        $ret=$ret->toArray();
        

        $auto_parameters= $this->getAutodetectedTemplateParameters($paths);
        foreach($auto_parameters as $key){
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
        // dump($parameter,$value);
        if(is_string($value) &&Str::startsWith($value,"@")){
            $value=apply_value($value);
        }
        
        if(isset($parameter["formatter"])){
            // dump($value, $parameter);
            $value=$this->applyFormatter($value, $parameter["formatter"], $parameter["formatter_parameters"]??[]);
        }
            
        

        return $value;
    }
    public function prepareValue($value,  $parameter_name, $parameter = null){
        if(!is_null($value) && $parameter){
            return $this->applyValue($value, $parameter);
            
        }else{
            return $this->preview_mode ? "<code>".strtoupper($parameter_name)."</code>" : "";
        }
    }



    /**
     * Inicializa los parametros que se le pasarán a la vista previa
     */
    public function prepareParameters($values=[], $exclude_functions=true){
        // dump($values);
        
        
        if(isset($values["orientation"])) $this->orientation =  $values["orientation"];
        if(isset($values["pagesize"])) $this->pagesize =  $values["pagesize"];
        if(isset($values["language"])) $this->language =  $values["language"];
        if(isset($values["margin"])) $this->margin =  $values["margin"];
        if(isset($values["pagination"])) $this->pagination =  $values["pagination"];

           
        // dump($parameters);
        $parameters = $this->getParameters($exclude_functions);
        
        // dd($parameters);
        
        $ret=[];

        // $functions=[];
        /** inicializo los parametros que no tengan valor con un tag */
        foreach($parameters as $parameter_name=>$parameter){
            if($parameter["type"]=="boolean"){
                $ret[$parameter_name] = ($values[$parameter_name]??null) ? true: false;
            }elseif($parameter["type"]=="collection"){
                $value=$values[$parameter_name]??null;
                $collection_functions =$this->getCollectionFunctionParameters($parameter_name);
                // dump($value);
                if($value){
                    foreach($value as $i=>$row){
                        foreach($row as $key=>$col_value){
                            $row[$key]=$this->prepareValue($col_value, $key, $parameter);
                        }
                        
                        
                        //añado valores de funciones
                         if($collection_functions){
                            foreach($collection_functions as $col_parameter_name=>$col_param_function){
                                // dump($param_function["function"], $this->{$param_function["function"]}($row));
                                $row[$col_parameter_name] = $this->{$col_param_function["function"]}($row);

                            }
                        }
                        $value[$i] = $row;
                        
                        
                    }
                    // dd($value);
                    // $values[$parameter_name]=$value;
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
        $this->template_attributes= $ret;

        $functions =$this->getFunctionParameters();
        foreach($functions as $parameter_name=>$param_function){
            $ret[$parameter_name] = $this->{$param_function["function"]}();

        }
        $this->template_attributes= $ret;

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

    

    public function getRows(){
        return $this->rows;
    }

    
    public function addRow($row=[]){
        $this->rows[]=$row;
    }

    private function prepareMultipleBody(&$parameters, $rows=null){
        $ret="";

        if($rows) $this->rows=$rows;

        $num_rows=count($this->rows);

        $columns=$this->getColumnsNameCombo();
// dd($columns);
        $parameters= array_merge($parameters, compact('columns','num_rows'));

        $ret.=$this->view('header', $parameters)->render();

        if($this->rows){
            // dd($function_cols);
            foreach($this->rows as $i=>$row){
                 
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
                        "last"=> ($i== (count($this->rows)-1))
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
    private function doGenerate($parameters=[], $rows=null){
 
        $parameters=$this->prepareParameters($parameters, false);
        // dd($parameters);
        
        if($this->multiple) $this->prepareMultipleBody($parameters, $rows);
        
        // dd($parameters);
        // dd($template_name);
        PDF::setOptions(['isRemoteEnabled' => true]);

        try{
            
            return PDF::loadView( $this->viewPath($this->templateName()), $parameters)->setPaper($this->pagesize, $this->orientation);
        
        }catch(Exception $e){
            dd($e);
        }
        
    }


    public function getFunctionColumns(){
        $ret=collect($this->getColumns(false))->filter(function($parameter){
            return isset($parameter["function"]);
        })->toArray();
        return $ret;
    }

    public function getColumnsValues($row){
        $function_cols=$this->getFunctionColumns();
            
        $row=collect($this->getColumns())->map(function($column, $key) use ($row){
        
            $ret= $this->applyValue($row[$key], $column);
            // dd($ret);
            return $ret;
        })->toArray();

        if($function_cols){
            foreach($function_cols as $row_parameter_name=>$row_param_function){
                $row[$row_parameter_name] = $this->{$row_param_function["function"]}($row);
            }
        }
        return $row;
    }

    public function getColumnsNameCombo(){
        // dd($report_columns);
        return array_map(function($column){
            return $column["label"];
        }, $this->getColumns(false));
    }

    public function getColumns($exclude_functions=true){
        if(uses_trait($this, 'Ajtarragona\Reports\Traits\MultipleReport')){

            $paths=[
                $this->getPath().DIRECTORY_SEPARATOR.'row'.$this->template_extension,
            ];
            
            // dd($auto_columns);
            $columns = $this->columns ?? [];

            $report_params=array_keys($this->getParameters(true));
            
            //les añado type y label si no lo tienen            
            foreach($columns as $key=>$column){
                // dump($column);
                if(in_array($key, $report_params) || ($exclude_functions && isset($column["function"]))){
                    //le quito los parametros generales del report
                    unset($columns[$key]);
                }else{
                    $columns[$key] = array_merge(["label"=>$key,'type'=>'text'], $column);
                }
            }
            
            // añado las columnas autodetectadas      
            $auto_columns= $this->getAutodetectedTemplateParameters($paths);
            // dump($auto_columns);
            foreach($auto_columns as $key){
                if(!in_array($key, $report_params) && !in_array($key, array_keys($this->columns??[]))){
                    $columns[$key] = ["type"=>"text", "label"=>$key ];
                }
            }
        //    dump($columns);
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
        $tmppath= storage_path('app'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'exported-reports');
        $files=new Filesystem;
        if (!$files->exists( $tmppath)) {
            $files->makeDirectory($tmppath, 0775, true);
        }
        // dd($tmppath);
        $zip_file = $tmppath.DIRECTORY_SEPARATOR.$this->getReportClassName().'.zip'; // Name of our archive to download
        // dd($tmppath.DIRECTORY_SEPARATOR.$zip_file);
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $path=$this->getPath();
        // dd($path);
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
        
                // extracting filename with substr/strlen
                $relativePath =   $this->getReportClassName() .'/'. substr($filePath, strlen($path) + 1);
        
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
// dd($zip);
        
        return response()->download($zip_file);


    }


    public function hasThumbnail(){
        return file_exists($this->getThumbnail());
    }
    
    public function getThumbnail(){
        return $this->getPath().DIRECTORY_SEPARATOR."thumbnail.jpg";

    }


    public function renderThumbnail(){
        if($this->hasThumbnail()){
            // $path=$this->getThumbnail();
            // dump($path);
            return '<img src="'. route('tgn-reports.thumbnail',$this->short_name) .'" class="img-fluid" title="Thumbnail '.$this->name().'"/>';
        }

    }

    public function generateThumbnail($parameters=null, $rows=null){
        $this->preview_mode=true;
        if(!$parameters){
            $parameters=[];
            $collections= $this->getCollectionParameterNames();
            $params=$this->getParameters(true);
            
            foreach($params as $key=>$param){
                if(!in_array($key,$collections)){
                    $parameters[$key] = $param["default_value"] ?? null;
                }
            }

            
            
            // dd($params);
            //prepare collection parameters
            if($collections){
                foreach($collections as $collection_name){
                    $numrows=rand(2,10);
                    $col_columns=$params[$collection_name]["columns"] ?? [];
                    if($numrows && $col_columns){
                        $collection_rows=[];
                        for($i=0;$i<$numrows;$i++){
                            // dd($col_columns);
                            $collection_rows[]= array_map(function($value) use ($i){ 
                                return $value["default_value"] ?? null;// ." ". ($i+1);
                            }, $col_columns);
                        }
                        $parameters[$collection_name] = $collection_rows;
                        
                    }
                }
            }
        }

        if($this->multiple){
            if(!$rows){
                // dd($request->all());
                $cols=$this->getColumns();
                $columns=[];
                foreach($cols as $key=>$col){
                    $columns[$key] = $col["default_value"] ?? null;
                }
                for($i=0;$i<rand(10,20);$i++){
                    $row=array_map(function($value) use ($i){ 
                        return $value;// ." ". ($i+1);
                    }, $columns);
                    $this->addRow($row);
                }
            }else{
               foreach($rows as $row) $this->addRow($row);
            }

        }
        // dd($this->rows);
        // dd($parameters);
        $gs_path=config('reports.gs_path');
        $path=$this->saveTmp($parameters);
        $path=storage_path('app'.DIRECTORY_SEPARATOR.$path);
        $target=$this->getThumbnail();
        $source=$path;
        $command="$gs_path -o $target -sDEVICE=jpeg -dLastPage=1 -dJPEGQ=100  $source";

        // dd($command);
        exec($command);


        // $path=$this->saveTmp([], []);
        // $path=storage_path('app'.DIRECTORY_SEPARATOR.$path);
        // // dd($path);
        // $imagick=new Imagick($path.'[0]');
        // $imagick->setImageFormat('png');
        // $imagick->setResolution (512,512);
        // echo $imagick;

        // $imagick->writeImages($this->getThumbnail());

    }
    

}
