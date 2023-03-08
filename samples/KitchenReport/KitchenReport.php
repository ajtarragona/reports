<?php

namespace Reports\KitchenReport;

use Ajtarragona\Reports\Models\BaseReport;

class KitchenReport extends BaseReport{

    public $short_name = "kitchen";
    public $name = "Kitchen";
    
    protected $parameters = [
        
        "param1" => [
            "type"=>"text",
            "label"=>"Param1",
            "formatter"=>["strtoupper","repeatNTimes"],
            'formatter_parameters'=>[ null, [2,"|"] ],
            'default_value'=>"@word"
        ],
        "title" => [
            "type"=>"text",
            "label"=>"Títol",
            'default_value'=>"@word"
        ],
        "subtitle" => [
            "type"=>"text",
            "label"=>"Subtítol",
            'default_value'=>"@sentence"
        ],
        "fecha" => [
            "type"=>"date",
            "label"=>"Fecha",
            "formatter"=>"formatData",
            'formatter_parameters'=>['l, d F Y']
        ]
        
    ];


    public function repeatNTimes($value, $num=1, $separator=";"){
        $ret=[];
        for($i=0;$i<$num;$i++) {
            $ret[]=$value;
        }
        return implode($separator,$ret);
    }
}