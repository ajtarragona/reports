<?php
namespace Ajtarragona\Reports\Rules;

use Ajtarragona\Reports\Services\ReportsService;
use Illuminate\Contracts\Validation\Rule;

class ReportFileIsValid implements Rule

{
  
    
    public function __construct()
    {
        //
    }

    
    public function passes($attribute, $value)
    {
        // dd($value);
        //comprobar que es un zip
        //comprobar que existe el archivo de config
        $config=ReportsService::getReportConfig($value);
        if(!$config) return false;

        //comprobar que existe short_name en el archivo config
        return array_key_exists("short_name", $config);
        
        
    }

    
    public function message()
    {
        return __('tgn-reports::reports.Report file is invalid');
    }

}