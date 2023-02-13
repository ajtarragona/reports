<?php

namespace Ajtarragona\Reports\Facades; 

use Illuminate\Support\Facades\Facade;

class ReportsFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'reports';
    }
}
