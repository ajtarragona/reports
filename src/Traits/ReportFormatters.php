<?php

namespace Ajtarragona\Reports\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

trait ReportFormatters
{
    
    public function formatData($value, $format='d/m/Y'){
        // dd($value);
        if($value){
            if($value instanceof Carbon){
                return $value->translatedFormat($format);
            }else {
                try{
                    $date = new Carbon($value);
                    // dump($date->locale());
                    return $date->translatedFormat($format);
                }catch(Exception $e){
                    // dd($e);
                }
            }
        }
        return $value;
    }

    public function formatMoney($value, $decimals=0, $coin="€"){
		if(!$value) $value= 0;
       
		// $num=number_format($value,$decimals,",",".");
		$num=number_format($value,$decimals,".","");
    
		//le quito los decimales a cero
		$tmp=explode(",",$num);
		$ret=$tmp[0];

		if(isset($tmp[1])){
			$decimals=rtrim($tmp[1],"0");
			if($decimals) $ret.=",".$decimals;
		}
		return $ret." ".$coin;

		
	}
    
    public function slugify($value){
        return Str::slug($value);
    }

    

}
