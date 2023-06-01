<?php

namespace Ajtarragona\Reports\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
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
       
		$num=number_format($value,$decimals,",",".");
		// $num=number_format($value,$decimals,".","");
    
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

    

    public function renderTable($array, $attributes=[]){
		// dump($array);
		$defaults=[
			"class"=>"",
			"style"=>"",
			"hideheader"=>false
		];

		$attributes=array_merge($defaults,$attributes);

		if(is_array($array) || $array instanceof Collection ) {
			$array=json_decode(json_encode($array),true);
			
			$ret="";
			if(count($array)>0){
				if(is_assoc($array)) $array=[$array];

				$headerrow=array_keys($array[0]);
				// dump($headerrow);
				$ret.= "<table class='table table-striped fullwidth ".$attributes["class"]."' style='".$attributes["style"]."' >";
				if(!$attributes["hideheader"]){
					$ret.= "<thead><tr>";
					foreach($headerrow as $name) $ret.= "<th><div>".$name."</div></th>";
					$ret.= "</tr></thead>";
				}
				$ret.= "<tbody>";
				foreach($array as $row){
					$ret.= "<tr>";
					foreach($row as $col){
						if(is_object($col) || is_array($col)) $col=implode("; ",$col);
						$ret.= "<td><div>".$col."</div></td>";
					}
					$ret.= "</tr>";
				}
				$ret.= "</table>";
			
				
			}
			// dd($ret;
			return $ret;
		}else{
			return $array;
		}
	}

}
