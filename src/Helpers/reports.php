<?php

use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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

if (! function_exists('is_collection')) {
	function is_collection($obj){
		return $obj && ($obj instanceof Collection || $obj instanceof EloquentCollection);

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
            
            $faker = FakerFactory::create();
            
            $callable=substr($value,1);
            $expression='return $faker->'.$callable.';';

            try{
                $value = eval($expression);
            }catch(Exception $e){
                //si no existe la funcion no hace nada, devolvera el valor sin modificar
            }
        }
        return $value;
	}
}


if (! function_exists('json_pretty')) {
	function json_pretty($string) {
	 	return json_encode($string, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
}

if (! function_exists('to_object')) {
	function to_object($array) {
		return json_decode(json_encode($array), FALSE);
		
	}
}



if (! function_exists('to_array')) {
	function to_array($object) {
	 	return json_decode(json_encode($object), true);
	}
}