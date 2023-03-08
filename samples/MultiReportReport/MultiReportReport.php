<?php

namespace Reports\MultiReportReport;

use Ajtarragona\Reports\Models\BaseReport;
use Ajtarragona\Reports\Traits\MultipleReport;

class MultiReportReport extends BaseReport{

    public $short_name = "multi_report";
    public $name = "Multi Report";

    use MultipleReport;

    
    protected $parameters = [
        
        "title" => [
            "type"=>"text",
            "label"=>"Títol"
        ]
        
    ];

    protected $columns  =[
        "col1"=> [
            "label"=>"Columna 1",
            "default_value"=>"@name"
        ],
        "col2"=> [
            "label"=>"Columna 2",
            "type"=>"number",
            "default_value"=>"@address"
        ],
        "col3"=> [
            "label"=>"Calculada",
            "type"=>"text",
            "function"=>"calculaValor"
        ]
    ];
    
    public function calculaValor($row){
        return strtoupper(array_first(explode(" ",$row["col1"])) . array_first(explode(" ",$row["col2"])));
    }
    /**
     * rows
     *
     * @return Collection
     */
    public function rows(){
        
    }
}