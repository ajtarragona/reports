<?php

namespace Ajtarragona\Reports\Controllers;

use Ajtarragona\Reports\Services\ReportsService;
use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use \Artisan;

class ReportsController extends Controller
{
    public function home($report_name=null, ReportsService $repo){

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

    

    public function preview($report_name, Request $request, ReportsService $repo){
        // dump($request->all());
        Artisan::call('vendor:publish',['--tag'=>'ajtarragona-reports-assets','--force'=>true]);
        $report=$repo->find($report_name);
        $collections= $report->getCollectionParameterNames();

        $parameters=$request->except(array_merge(['_token','submit_action','num_rows','columns'], $collections));
        // dd($parameters); 
        $rows=null;

        if($report->multiple){
            // dd($request->all());
            $rows=[];
            if($request->num_rows){
                for($i=0;$i<apply_value($request->num_rows);$i++){
                    $rows[]= array_map(function($value) use ($i){ 
                        return $value;// ." ". ($i+1);
                    }, $request->columns);
                }
            }

        }

        //prepare collection parameters
        if($collections){
            foreach($collections as $collection_name){
                $numrows=$request->{$collection_name}["num_rows"];
                $columns=$request->{$collection_name}["columns"];
                if($numrows && $columns){
                    $rows=[];
                    for($i=0;$i<apply_value($numrows);$i++){
                        $rows[]= array_map(function($value) use ($i){ 
                            return $value;// ." ". ($i+1);
                        }, $columns);
                    }
                    $parameters[$collection_name] = $rows;
                    
                }
            }
        }
        // dd($parameters);

        return $report->stream($parameters, $rows);
        
        
    }


    public function export($report_name, Request $request, ReportsService $repo){

        $report=$repo->find($report_name);
        return $report->export();
        
    }
   
    
}
