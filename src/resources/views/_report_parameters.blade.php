

{{-- @dump($report_parameters) --}}
<form method="post" action="{{ route('tgn-reports.generate',$report->short_name)}}" target="report-preview"> 
    @csrf
    <div class="card mt-3 mb-3">
        <div class="card-header d-flex justify-content-between align-items-center pe-2">
            <div class="btn-group">
                <button class="btn btn-secondary btn-sm" type="submit">Run <i class="bi bi-play"></i></button>
                <button class="btn btn-light btn-sm border" type="reset"  value="clear"><i class="bi bi-trash-fill"></i></button>
            </div>
            @if($parameters || ($orientations && count($orientations)>1)  || ($pagesizes && count($pagesizes)>1)  || ($languages && count($languages)>0) )
                
                <a class="flex-1 ps-2 text-secondary text-decoration-none text-nowrap" data-bs-toggle="collapse" href="#all-parameters" role="button">
                    <span class="h6">Paràmetres</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
             @endif
            
        </div>

        
        <div class="collapse show" id="all-parameters">
            <ul class="list-group list-group-flush">
                @if($pagesizes)
                    @if(count($pagesizes)>1)
                    
                        @include('tgn-reports::_report_parameter-select',[
                            'name'=>'pagesize',
                            'options'=> $pagesizes,
                            'value'=>$report_parameters['pagesize'] ?? null,
                            'label'=> 'Pagesize'
                        ])
                    
                    @endif
                @endif

                @if($orientations)
                    @if(count($orientations)>1)
                        
                            @include('tgn-reports::_report_parameter-select',[
                                'name'=>'orientation',
                                'options'=> $orientations,
                                'value'=>$report_parameters['orientation'] ?? null,
                                'label'=> 'Orientation'
                            ])

                            
                          
                    @endif
                @endif

                @if($languages)
                    @if(count($languages)>1)
                    
                            @include('tgn-reports::_report_parameter-select',[
                                'name'=>'language',
                                'options'=> $languages,
                                'value'=>$report_parameters['language'] ?? null,
                                'label'=> 'Language'
                            ])
                               
                             
                    @endif
                @endif
                @if($parameters)
                    @foreach($parameters as $parameter_name=>$parameter)
                        @includeFirst(['tgn-reports::_report_parameter-'.$parameter["type"], 'tgn-reports::_report_parameter-text'],[
                            'name'=>$parameter_name,
                            'options'=> $parameter["options"] ?? null,
                            'value'=>$report_parameters[$parameter_name] ?? null,
                            'label'=> $parameter["label"]
                        ])
                    @endforeach
                 @endif
            </ul>
        </div>
    </div>
</form>
