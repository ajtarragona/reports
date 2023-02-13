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
