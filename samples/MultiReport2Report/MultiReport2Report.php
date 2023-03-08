<?php

namespace Reports\MultiReport2Report;

use Ajtarragona\Reports\Models\BaseReport;
use Ajtarragona\Reports\Traits\MultipleReport;

class MultiReport2Report extends BaseReport{

    public $short_name = "multi_report2";
    public $name = "Multi Report2";

    use MultipleReport;

    
    protected $parameters = [
        
        "numcols"=>[
            "label"=>"Num Columnes (1,2,3 o 4)",
            "type"=>"number",
            'default_value'=>"@randomElement([1,2,3,4])"
        ]
        
    ];

    
    protected $columns= [
        "frase"=>[
            "label"=>"Frase",
            'default_value'=>'@sentence'
        ],
        "title"=>[
            "label"=>"Títol",
            'default_value'=>'@word'
        ],
        "color"=>[
            "label"=>"Color",
            'default_value'=>"@randomElement(['success','secondary','warning','danger'])"
        ]
        
    ];
    
   
    
}