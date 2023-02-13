<?php
namespace Ajtarragona\Reports\Services;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class ReportsService{
    
    const SUFFIX = "Report";
    const BASE_NAMESPACE = "Reports";
    const BASE_PATH = "storage/app/reports";
    const VIEWS_NAMESPACE = "tgn-report-";

   
    /** Retorna un report pasando el nombre corto */    
    public function find($name, $settings=null){
        $obj=null;

        $classpath = self::reportClassPath($name);
        
        // dump("CLASSPATH:".$classpath, class_exists($classpath));
        if (class_exists($classpath)) {
            $obj=new $classpath();
            if($settings){
                $obj->settings=$settings;
            }
        }
        // dump($obj);
        // die();
        return $obj;
    }

  
    /** Retorna todos los reports */
    public function all(){
        $ret=collect();
       
        $files = new Filesystem;

        // dump($this->config);
        // dd(base_path(self::reportsBasePath()));
        $report_names=[];
        try{
            $report_names=$files->directories(base_path(self::reportsBasePath()));
            // dd($report_names);
        }catch(DirectoryNotFoundException $e){
            $files->makeDirectory(base_path(self::reportsBasePath()));
        }

        foreach($report_names as $report_name){
            $config_path=$report_name.DIRECTORY_SEPARATOR."config.php";
            // dd($files->exists());
            // dd($config_path, $files->exists($config_path));
            if($files->exists($config_path)){
                $config = include $config_path;
                try{
                    $report=$this->find($config['short_name']);
                    if($report){
                        $ret->push($report);
                    }
                }catch(Exception $e){
                    // dd($e);
                }
            }
        }
        // dd($ret);
        return $ret;
    }


    public static function reportsBasePath($more=null){
        return str_replace(["/","\\"],DIRECTORY_SEPARATOR, self::BASE_PATH. ($more?(DIRECTORY_SEPARATOR.$more):''));
    }

    public static function reportNamespace($report_name){
        $className=Str::studly($report_name). self::SUFFIX;
        return "\\".ReportsService::BASE_NAMESPACE."\\".$className; 
    }

    public static function reportClassPath($report_name){
        $className=Str::studly($report_name). self::SUFFIX;
        return self::reportNamespace($report_name)."\\".$className; 
    }

    public static function reportPath($report_name){
        $className=Str::studly($report_name). self::SUFFIX;
        return base_path(self::reportsBasePath($className));
    }
    
    public static function getConfigFilePath($report_name){
        return self::reportPath($report_name).DIRECTORY_SEPARATOR."config.php";
    }

    public static function getConfigFile($report_name){

        $config_path=self::getConfigFilePath($report_name);
        // dd($files->exists());
        // dump($config_path);
        $config=[];
        $files = new Filesystem;

        if($files->exists($config_path)){
            $config = include $config_path;
        }
        return $config;
    }


    public function getSession($report_name, $parameter=null){
        $ret= session('parameters_'.$report_name, []); 
        if($parameter){
            return $ret[$parameter] ?? null;
        }else{
            return $ret;
        }
        
    }

    public function setSession($report_name, $value){
        session(['parameters_'.$report_name => $value]);
    }
    public function clearSession($report_name){
        session(['parameters_'.$report_name => null]);
    }


    
}