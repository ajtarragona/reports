<?php


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