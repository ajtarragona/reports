<?php

return [
	
	"backend" => env('REPORTS_BACKEND',false),
	/*
    |--------------------------------------------------------------------------
    | Stubs Path
    |--------------------------------------------------------------------------
    |
    | The stubs path directory to generate crud. You may configure your
    | stubs paths here, allowing you to customize the own stubs of the
    | model,controller or view. Or, you may simply stick with the CrudGenerator defaults!
    |
    | Example: 'stub_path' => resource_path('path/to/views/stubs/')
    | Default: "default"
    | Files:
    |       Controller.stub
    |       Model.stub
    |       views/
    |            create.stub
    |            edit.stub
    |            form.stub
    |            form-field.stub
    |            index.stub
    |            show.stub
    |            view-field.stub
    */

    'stub_path' => 'default',
    'default_language' => 'ca',
    'default_pagesize' => 'A4',
    'default_orientation' => 'portrait',
    'default_margin' => 'lg',
    'gs_path' => env('GHOSTSCRIPT_PATH','gswin64c.exe') // path to gs executable
];

