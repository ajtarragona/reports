<?php

namespace Ajtarragona\Reports\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

trait ReportFormatters
{
    
    public function formatData($value, $format='d/m/Y'){
       
        if($value){
            if($value instanceof Carbon){
                return $value->translatedFormat($format);
            }else {
                try{
                    $date=new Carbon($value);
                    // dump($date->locale());
                    return $date->translatedFormat($format);
                }catch(Exception $e){

                }
            }
        }
        return $value;
    }
    
    public function slugify($value){
        return Str::slug($value);
    }

    

}
