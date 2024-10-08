<?php

namespace Ajtarragona\Reports\Controllers;

use Ajtarragona\Reports\Services\ReportsService;
use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use \Artisan;

class ReportsController extends Controller
{
    public function login(){
        return view("tgn-reports::pass");
    }


    public function dologin(Request $request){
        if($request->password == config("reports.backend_password")){
            session(['reports_login'=>"OK"]);
            return redirect()->route('tgn-reports.home');
        }else{
            return redirect()->route('tgn-reports.login')->withErrors(["password" => "Wrong password"])->withInput();
        }
    }

    
    public function logout(){
        session()->forget('reports_login');
        return redirect()->route('tgn-reports.home');
    }


    public function home(ReportsService $repo, $report_name=null){
        Artisan::call('vendor:publish',['--tag'=>'ajtarragona-reports-assets','--force'=>true]);
        
        $reports=$repo->all();
        // dd($reports);
        $current_report=null;

        $args=compact('reports','report_name');

        if($report_name){
            $current_report = $repo->find($report_name) ;
            $pagesizes=$current_report->getPagesizesCombo();
            $orientations=$current_report->getOrientationsCombo();
            $languages=$current_report->getLanguagesCombo();
            $parameters=$current_report->getParameters();
            $args=array_merge($args, compact('current_report','pagesizes','orientations','languages','parameters'));
        }

        
        
        return view("tgn-reports::welcome", $args);
    }

    

    public function thumbnail(Request $request, ReportsService $repo, $report_name){
        $report=$repo->find($report_name);
        $path=$report->getThumbnail();
        
        return response()->streamDownload(function() use ($path) {
            echo file_get_contents($path);
            
        }, $report_name.".png", ['content-type' => 'image/png']);



    }

    
    public function generateThumbnail(Request $request, ReportsService $repo, $report_name){
        $report=$repo->find($report_name);
        $report->generateThumbnail();
        return redirect()->back();
    }



    public function preview(Request $request, ReportsService $repo, $report_name){
        // dump($request->all());
        Artisan::call('vendor:publish',['--tag'=>'ajtarragona-reports-assets','--force'=>true]);
        $report=$repo->find($report_name);
        $report->preview_mode=true;
        
        $collections= $report->getCollectionParameterNames();
// dd($collections);
        $parameters=$request->except(array_merge(['_token','submit_action','num_rows','columns'], $collections));
        // dd($parameters); 
        $rows=null;

        if($report->multiple){
            // dd($request->all());
            $rows=[];
            if($request->num_rows){
                // dump($request->columns);
                for($i=0;$i<apply_value($request->num_rows);$i++){
                    $rows[]= array_map(function($value) use ($i){ 
                        if(is_array($value) && isset($value["columns"]) && $value["num_rows"]){
                            //trato las colecciones multiples
                            $ret=[];
                            for($i=0;$i<apply_value($value["num_rows"]);$i++){
                                $retrow=[];
                                foreach($value["columns"] as $col_name=>$col_value){
                                    $retrow[$col_name] = apply_value($col_value);
                                }
                                $ret[]=$retrow;
                            }
                            return $ret;
                        }else{
                            return $value;// ." ". ($i+1);
                        }
                    }, $request->columns);
                }
                // dd($rows);
            }

        }

        
        //prepare collection parameters
        if($collections){
            foreach($collections as $collection_name){
                // $parameters[$collection_name] =  $report->prepareCollection($collection_name, $request->{$collection_name}["num_rows"] );
                
                $numrows=$request->{$collection_name}["num_rows"];
                $columns=$request->{$collection_name}["columns"];
                if($numrows && $columns){
                    $rows2=[];
                    for($i=0;$i<apply_value($numrows);$i++){
                        $rows2[]= array_map(function($value) use ($i){ 
                            return $value;// ." ". ($i+1);
                        }, $columns);
                    }
                    $parameters[$collection_name] = $rows2;
                    
                }
            }
        }
        // dd($parameters);
        // dd($rows);
        if($request->regenerate_thumbnail) $report->generateThumbnail($parameters, $rows);
        return $report->stream($parameters, $rows);
        
        
    }


    public function export(Request $request, ReportsService $repo, $report_name){

        $report=$repo->find($report_name);
        return $report->export();
        
    }
   
    
}
