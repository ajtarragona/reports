<?php

namespace Reports\WithCollectionsReport;

use Ajtarragona\Reports\Models\BaseReport;

class WithCollectionsReport extends BaseReport{

    public $short_name = "with_collections";
    public $name = "With Collections";

    
    protected $parameters = [
        
        "param1" => [
            "type"=>"text",
            "label"=>"Param1",
            "default_value"=>'@sentence',
            "formatter"=>["strtoupper"]
        ],
        "cosas" => [
            "type"=>"collection",
            "label"=>"Cosas",
            "columns" => [
                "col1"=>[
                    "label"=>"Columna 1",
                    'type'=>'text',
                    "default_value"=>'@word',
            
                ],
                'col2'=>[
                    'type'=>'number',
                    "label"=>"Columna 2",
                    "default_value"=>'@sentence',
            
                ]
            ]
        ]
        
    ];

    protected $excluded_parameters = [ "cosa" ];
}