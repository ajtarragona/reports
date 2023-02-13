<?php

namespace Ajtarragona\Reports\Controllers;

use Ajtarragona\Reports\Services\ReportsService;
use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;

class ReportsController extends Controller
{
    public function home($report_name=null, ReportsService $repo){

        $reports=$repo->all();
        // dd($reports);
        $current_report=null;
        $report_parameters=[]; //$repo->getSession($report_name);

        $args=compact('reports','report_name','report_parameters');

        if($report_name){
            $current_report = $repo->find($report_name) ;
            $pagesizes=$current_report->getPagesizes();
            $orientations=$current_report->getOrientations();
            $languages=$current_report->getLanguages();
            $parameters=$current_report->getTemplateParameters();
            $args=array_merge($args, compact('current_report','pagesizes','orientations','languages','parameters'));
        }

        
        
        return view("tgn-reports::welcome", $args);
    }

    
    public function preview($report_name, Request $request, ReportsService $repo){
        // dd($request->all());
        // if($request->submit_action=="clear"){
        //     $repo->clearSession($report_name);
            
        // }else{
        //     $repo->setSession($report_name, $request->except(['_token','submit_action']));
        // }
        
        $report=$repo->find($report_name);
        // dd($report);
        $parameters=$request->except(['_token','submit_action']);
        $parameters=$report->templatePreviewParameters($parameters);
        // dd($parameters);
        return $report->stream($parameters);
        
        
    }

   
    

    public function generate($report_name, Request $request, ReportsService $repo){
        $report_parameters=$repo->getSession($report_name);

// dd( $report_parameters);
        $report=$repo->find($report_name);
        $parameters=$report->templatePreviewParameters($report_parameters);
        // dd($parameters);
        return $report->download($parameters);
    }
}
