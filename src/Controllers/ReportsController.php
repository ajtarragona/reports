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
            $pagesizes=$current_report->getPagesizesCombo();
            $orientations=$current_report->getOrientationsCombo();
            $languages=$current_report->getLanguagesCombo();
            $parameters=$current_report->getTemplateParameters();
            $args=array_merge($args, compact('current_report','pagesizes','orientations','languages','parameters'));
        }

        
        
        return view("tgn-reports::welcome", $args);
    }

    
    public function generate($report_name, Request $request, ReportsService $repo){
        //  dd($request->all());
        $report=$repo->find($report_name);
        // dd($report);
        $parameters=$request->except(['_token','submit_action']);
        $parameters=$report->templatePreviewParameters($parameters);
        // dd($parameters);
        return $report->stream($parameters);
        
        
    }

   
    
}
