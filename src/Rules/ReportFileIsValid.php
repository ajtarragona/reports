<?php
namespace Ajtarragona\Reports\Rules;
use Illuminate\Contracts\Validation\Rule;

class ReportFileIsValid implements Rule

{
  
    
    public function __construct()
    {
        //
    }

    
    public function passes($attribute, $value)
    {
        
        //comprobar que es un zip
        //comprobar que existe el archivo de config
        //comprobar que existe short_name en el archivo config

        
        return true;
        
    }

    
    public function message()
    {
        return __('tgn-reports::reports.Report file is invalid');
    }

}