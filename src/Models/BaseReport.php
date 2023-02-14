<?php

namespace Ajtarragona\Reports\Models;

use Ajtarragona\Reports\Services\ReportsService;
use Ajtarragona\Reports\Traits\ReportFormatters;
use Exception;
use PDF;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Storage;
use Illuminate\Support\Arr;

class BaseReport
{
    
    use ReportFormatters;

    public $short_name = "";
    
    public $pagesize;
    public $orientation;
    public $margin;
    public $language;
    
    public $pagination=true;
    public $multiple = false;
    
   
    protected $entities=null;

    protected $template_name="template";
    protected $template_extension=".blade.php";
    
    protected $prepend = [];
    protected $append = [];

    protected $engine = "dompdf";


    
    protected $config = [];
    protected $parameters = [];

    
    public function __construct()
    {
        $this->orientation = Arr::first($this->config('orientations',[config('reports.default_orientation')])) ;
        $this->pagesize = Arr::first($this->config('pagesizes',[config('reports.default_pagesize')])) ;
        $this->language = Arr::first($this->config('languages',[config('reports.default_language')])) ;
        $this->margin = $this->config('margin',config('reports.default_margin')) ;
        $this->pagination = $this->config('pagination',true) ;
        $this->multiple = $this->config('multiple',false) ;

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
    
    
    public function getTemplateParameters(){

        //autodetected parameters
        $path=$this->getTemplatePath();
        // dd($path);
        $parameters=[];
        if(file_exists($path)){
            $content=file_get_contents($path);

            preg_match_all('/\$[0-9a-zA-Z_]+/', $content, $matches);

            if($matches && is_array($matches[0])){
                $parameters=array_map(function($item){
                    return trim($item,'$');
                }, $matches[0]);
            }
            // return $matches);

            
        }
        $auto = array_unique($parameters);
        $ret=$this->parameters;
        foreach($auto as $key){
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


    /**
     * Inicializa los parametros que se le pasarán a la vista previa
     */
    public function prepareParameters($values=[]){
        
        
        if(isset($values["orientation"])) $this->orientation =  $values["orientation"];
        if(isset($values["pagesize"])) $this->pagesize =  $values["pagesize"];
        if(isset($values["language"])) $this->language =  $values["language"];
        if(isset($values["margin"])) $this->margin =  $values["margin"];
        if(isset($values["pagination"])) $this->pagination =  $values["pagination"];

           
        $parameters= $this->getTemplateParameters();
        // dd($parameters, $values);
        $ret=[];

        /** inicializo los parametros que no tengan valor con un tag */
        foreach($parameters as $parameter_name=>$parameter){
            if($parameter["type"]!="boolean"){
                $value=$values[$parameter_name]??null;
                if(!is_null($value)){
                    if(isset($parameter["formatter"])) $value=$this->applyFormatter($value, $parameter["formatter"], $parameter["formatter_parameters"]??[]);
                    $ret[$parameter_name] = $value ;
                }else{
                    $ret[$parameter_name] ="<code>".strtoupper($parameter_name)."</code>";
                }
            }else{
                $ret[$parameter_name] = ($values[$parameter_name]??null) ? true: false;
            }
            
        }
        
        $ret['orientation']=$this->orientation;
        $ret['pagesize']=$this->pagesize;
        $ret['language'] = $this->language;
        $ret['margin'] = $this->margin;
        $ret['pagination'] = $this->pagination;

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
    public function stream($request=[]){
        $pdf=$this->doGenerate($request);
        
        if($this->prepend || $this->append){
            $now = $this->randomfilename();

            return $pdf->save($now.".pdf", "browser");
        }else{
            return $pdf->stream();
        }
    }


     /** 
     * General el PDF
     * descarga el archivo
     */
    public function download($request=[]){
        $pdf=$this->doGenerate($request);
       
        if($this->prepend || $this->append){
            $now = $this->randomfilename();
                
            return $pdf->save($now.".pdf", "download");
        }else{
            return $pdf->download();
        }
        
    }


    /** 
     * General el PDF
     * Devuelve el contenido binario
     */
    public function generate($request=[]){
        $pdf=$this->doGenerate($request);
    
        if($this->prepend || $this->append){
            $now = $this->randomfilename();
                
            return $pdf->save($now, "string");

        }else{
            return $pdf->output();
        }       
    }

    
    public function saveTmp($request=[]){
        $pdf=$this->generate($request);

        if($pdf){
            $now = $this->randomfilename();
            $path='tmp/reports/'.$now;
            $ret=Storage::put($path, $pdf);
            if($ret) return $path;

        }
        return null;

    }

    

    protected function addPdfViews(&$pdfMerger, $viewnames, $args=[]){
        if($viewnames){
            foreach($viewnames as $viewname){
                $this->addPdfView($pdfMerger, $viewname, $args);
            }       
        }
    }


    private function addPdfView(&$pdfMerger, $viewname, $args=[]){
        // dump($viewname);
        $now = $this->randomfilename();
        $pdf=PDF::loadView( $this->viewPath($viewname), $args )->setPaper($this->pagesize, $this->orientation);
        // dd($pdf);
        Storage::put('tmp/'.$now, $pdf->output());
        $pdfMerger->addPDF(storage_path('app/tmp/'.$now));

    }


    
    /** 
     * General el PDF
     * Devuelve el contenido binario
     */
    private function doGenerate($parameters=[]){
        
       
        // if($this->engine =="dompdf"){
            $parameters=$this->prepareParameters($parameters);
            // dd($template_name);
            
            PDF::setOptions(['isRemoteEnabled' => true]);
            
            
            try{
            
            
                if($this->prepend || $this->append){
                    Storage::makeDirectory('tmp');
                        
                    $pdfMerger = PDFMerger::init();
                    
                    $this->addPdfViews($pdfMerger, $this->prepend, $parameters);
                    
                    $now = $this->randomfilename();
                    $main_pdf= PDF::loadView( $this->viewPath($this->template_name), $parameters)->setPaper($this->pagesize, $this->orientation);
                    Storage::put('tmp/'.$now, $main_pdf->output());
                    $pdfMerger->addPDF(storage_path('app/tmp/'.$now));


                    $this->addPdfViews($pdfMerger, $this->append, $parameters);
                    
                
                    $pdfMerger->merge(); //For a normal merge (No blank page added)                
                    return $pdfMerger;

                
                }else{
                    return PDF::loadView( $this->viewPath($this->templateName()), $parameters)->setPaper($this->pagesize, $this->orientation);

                }
            
            }catch(Exception $e){
                // dd($e);
            }
        
    }

    
    

}
