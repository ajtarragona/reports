

<form method="post" action="{{ route('tgn-reports.preview',$report->short_name)}}" target="report-preview"> 
    @csrf
    <div class="card mt-3 mb-3">
        <div class="card-header d-flex justify-content-between align-items-center ps-2">
            <div>
            @if($parameters || ($orientations && count($orientations)>1)  || ($pagesizes && count($pagesizes)>1)  || ($languages && count($languages)>0) )
                
                <a class="flex-1 ps-2 text-secondary text-decoration-none text-nowrap" data-bs-toggle="collapse" title="Paràmetres" href="#all-parameters" role="button">
                    {{-- <span class="h6">Paràmetres</span> --}}
                    <i class="bi bi-gear"></i>
                </a>
             @endif
            </div>
            <div class="btn-group">
                <button class="btn btn-light btn-sm border" type="reset"  value="clear"><i class="bi bi-x"></i></button>
                <button class="btn btn-secondary btn-sm" type="submit">Test <i class="bi bi-play"></i></button>
            </div>
            
        </div>

        
        <div class="collapse " id="all-parameters">
           
            <ul class="list-group list-group-flush">
                <li class="list-group-item list-group-item-primary">
                    <div class="form-check form-switch">
                        <input class="form-check-input " type="checkbox" value="1" name="regenerate_thumbnail" role="switch" id="parameter-regenerate_thumbnail" >
                        <label class="form-check-label" for="parameter-regenerate_thumbnail">Regenerar miniatura</label>
                    </div>
                </li>
                @if($pagesizes)
                    @if(count($pagesizes)>1)
                    
                        @include('tgn-reports::_report_parameter-select',[
                            'name'=>'pagesize',
                            'options'=> $pagesizes,
                            'label'=> 'Pagesize'
                        ])
                    
                    @endif
                @endif

                @if($orientations)
                    @if(count($orientations)>1)
                        
                            @include('tgn-reports::_report_parameter-select',[
                                'name'=>'orientation',
                                'options'=> $orientations,
                                'label'=> 'Orientation'
                            ])

                            
                          
                    @endif
                @endif

                @if($languages)
                    @if(count($languages)>1)
                    
                            @include('tgn-reports::_report_parameter-select',[
                                'name'=>'language',
                                'options'=> $languages,
                                'label'=> 'Language'
                            ])
                               
                             
                    @endif
                @endif

               

                @if($parameters)
                    {{-- @dump($parameters) --}}
                    @foreach($parameters as $parameter_name=>$parameter)
                        @includeFirst(['tgn-reports::_report_parameter-'.$parameter["type"], 'tgn-reports::_report_parameter-text'],[
                            'name'=>$parameter_name,
                            'options'=> $parameter["options"] ?? null,
                            'value'=>$parameter["default_value"]??null,
                            'label'=> $parameter["label"]
                        ])
                    @endforeach
                 @endif


                 @if($report->multiple)
                    
                    @include('tgn-reports::_report_parameter-number',[
                        'name'=>'num_rows',
                        'options'=> null,
                        'value'=> 15,
                        'label'=> 'Files'
                    ])
                    
                    <li class="list-group-item list-group-item-secondary">
                        Row parameters
                    </li>

                    {{-- @dump($report->getColumns()) --}}
                    @foreach($report->getColumns() as $column_key=>$column)
                        @includeFirst(['tgn-reports::_report_parameter-'.$column["type"], 'tgn-reports::_report_parameter-text'],[
                            'name'=>'columns['.$column_key.']',
                            'options'=> $column["options"] ?? null,
                            'value'=>$column["default_value"]??null,
                            'label'=> $column["label"]
                        ])
                        
                        
                    @endforeach
                @endif

            </ul>
        </div>
    </div>
</form>
