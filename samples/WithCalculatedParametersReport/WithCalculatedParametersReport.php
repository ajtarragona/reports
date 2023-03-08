<?php

namespace Reports\WithCalculatedParametersReport;

use Ajtarragona\Reports\Models\BaseReport;

class WithCalculatedParametersReport extends BaseReport{

    public $short_name = "with_calculated_parameters";
    public $name = "With Calculated Parameters";

    protected $parameters = [
        "param1"=>[
            "type"=>"number",
            "label"=>"Param 1",
            "default_value"=>"@numberBetween(1,10)"
        ],
        "param2"=>[
            "type"=>"number",
            "label"=>"Param 2",
            "default_value"=>"@numberBetween(1,10)"
        ],
        "suma"=>[
            "type"=>"number",
            "label"=>"Suma",
            "function" => "suma"
        ],
        "registres" => [
            "type"=>"collection",
            "label"=>"Registres",
            "columns" => [
                "caca1"=>[
                    "type"=>"number",
                    "label"=>"Caca 1",
                    "default_value"=>"@numberBetween(1,10)"
                ],
                "caca2"=>[
                    "type"=>"number",
                    "label"=>"Caca 2",
                    "default_value"=>"@numberBetween(1,10)"
                ],
                "subsuma"=>[
                    "type"=>"number",
                    "label"=>"Subsuma",
                    "function" => "subSuma"
                ],
               
            ]
        ]
        
    ];

    protected  $excluded_parameters=[ "registre"];

    protected function subSuma($row){
        // dump("subSuma", $row["caca1"], $row["caca2"]);
        return ((double)$row["caca1"]??0)  + ((double)$row["caca2"]??0) ;
        
    }

    protected function suma(){
        // dd($this->template_attributes["param1"], $this->template_attributes["param2"]);
        return ((double)$this->template_attributes["param1"]??0)  + ((double)$this->template_attributes["param2"]??0);
    }

}