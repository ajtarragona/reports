<?php
namespace Ajtarragona\Reports\Services;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use ZipArchive;
use File;

class ReportsService{
    
    const SUFFIX = "Report";
    const BASE_NAMESPACE = "Reports";
    const BASE_PATH = "app/report-templates";
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
        // dump($report_names);
        foreach($report_names as $report_name){
            $config_path=$report_name.DIRECTORY_SEPARATOR."config.php";
            // dd($files->exists());
            // dd($config_path, $files->exists($config_path));
            if($files->exists($config_path)){
                $config = include $config_path;
                try{
                    // dump($config['short_name']);
                    $report=$this->find($config['short_name']);
                    // dd($report);
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

    public static function storagePath(){
        return 'storage'.DIRECTORY_SEPARATOR.self::BASE_PATH;
    }
    public static function reportsBasePath($more=null){
        return str_replace(["/","\\"],DIRECTORY_SEPARATOR, self::storagePath(). ($more?(DIRECTORY_SEPARATOR.$more):''));
    }

    public static function reportClassName($report_name){
        return Str::studly($report_name). self::SUFFIX;
    }
    public static function reportNamespace($report_name){
        return "\\".ReportsService::BASE_NAMESPACE."\\".self::reportClassName($report_name); 
    }

    public static function reportClassPath($report_name){
        return self::reportNamespace($report_name)."\\".self::reportClassName($report_name); 
    }

    public static function reportPath($report_name){
        return base_path(self::reportsBasePath(self::reportClassName($report_name)));
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


   
    

    public static function getReportConfig($report_file){
        $zip = new ZipArchive;
        $status = $zip->open($report_file->getRealPath());
        if ($status !== true) {
            return [];
        }else{

            $storageDestinationPath= storage_path('app'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'imported-reports');
            $files=new Filesystem;
            if (!$files->exists( $storageDestinationPath)) {
                $files->makeDirectory($storageDestinationPath, 0775, true);
            }
            $zip->extractTo($storageDestinationPath);
            $zip->close();
            $config_path=$storageDestinationPath . DIRECTORY_SEPARATOR.basename($report_file->getClientOriginalName(), ".zip").DIRECTORY_SEPARATOR."config.php";
            try{
                return include $config_path;
            }catch(Exception $e){
                return [];
            }
        }
    }


    public static function getReportShortName($report_file){
        $config= self::getReportConfig($report_file);
        return $config["short_name"] ?? null;
    }


    public static function reportExists($report_file){
        $name=basename($report_file->getClientOriginalName(), ".zip");
        // dump($name);
        $storageDestinationPath= storage_path(ReportsService::BASE_PATH);
        return File::exists( $storageDestinationPath.DIRECTORY_SEPARATOR.$name);
    }


    /** Uploadea un nuevo report */
    public static function uploadReport($report_file){
        // dd($report_file);
        $zip = new ZipArchive;
        $files = new Filesystem;

        $status = $zip->open($report_file->getRealPath());
        if ($status !== true) {
            throw new Exception($status);
        }else{
            // dd(ReportsService::BASE_PATH);
            $storageDestinationPath= storage_path(self::BASE_PATH);
            // dd($storageDestinationPath); 
            if (!$files->exists( $storageDestinationPath)) {
                $files->makeDirectory($storageDestinationPath, 0775, true);
            }
            $zip->extractTo($storageDestinationPath);
            $zip->close();
        }
        
    }

    
}