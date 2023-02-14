<?php

use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;

if (! function_exists('tgn_reports')) {
	function tgn_reports() {
        return new \Ajtarragona\Reports\Services\ReportsService;
    }
}

if (! function_exists('c')) {
	function c($name) {
        return "<code>{$name}</code>";
    }
}

if (! function_exists('array_permutations')) {
    function array_permutations($items, $perms = [],&$ret = []) {
        // initialize by adding the empty set
        // $results = array(array( ));
 
        // foreach ($items as $element)
        //     foreach ($results as $combination)
        //         array_push($results, array_merge(array($element), $combination));
    
        // return $results;

        
        if (empty($items)) {
            $ret[] = $perms;
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                array_permutations($newitems, $newperms,$ret);
            }
        }
        return $ret;
    }
}

if(!function_exists('uses_trait')){
	function uses_trait($obj, $name){
		return  in_array($name, array_keys(class_uses($obj)));

	}
}



if(!function_exists('apply_value')){
	function apply_value($value){
        if(Str::startsWith($value,"@")){
            // dd("applyValue",$value);
            $method=substr($value,1); //quito la arroba
            $params=[];
            // dd($method);
            if(Str::contains($method,"(")){
                $tmp=substr($method, 0,strpos($method,"("));
                $params=trim(substr($method,strpos($method,"(")),"()");
                $params=explode(",",$params);
                $method=$tmp;
                
            }
            
            $faker = FakerFactory::create();
            $value=$faker->{$method}(...$params) ?? $value;
        }
        return $value;
	}
}