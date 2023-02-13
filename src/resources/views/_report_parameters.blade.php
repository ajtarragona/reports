

{{-- @dump($report_parameters) --}}
<form method="post" action="{{ route('tgn-reports.preview',$report->short_name)}}" target="report-preview"> 
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
                        <li class="list-group-item p-0">
                            <div class="form-floating ">
                                
                                <select class="form-select border-0" id="pagesize-select" name="pagesize">
                                    @foreach($pagesizes as $pagesize)
                                        <option value="{{$pagesize}}" {{ ( ($report_parameters['pagesize'] ?? null)  == $pagesize) ? 'selected':''}} >{{$pagesize}}</option>
                                    @endforeach
                                </select>
                                <label for="pagesize-select" >Pagesize</small>
                                
                            </div>
                            
                        </li>
                    @endif
                @endif

                @if($orientations)
                    @if(count($orientations)>1)
                        <li class="list-group-item p-0">
                            <div class="form-floating ">
                                
                                <select class="form-select border-0" id="orientation-select" name="orientation">
                                    @foreach($orientations as $orientation)
                                        <option value="{{$orientation}}" {{ ( ($report_parameters['orientation'] ?? null)  == $orientation) ? 'selected':''}}  >{{$orientation}}</option>
                                    @endforeach
                                </select>
                                <label for="orientation-select" >Orientation</small>
                                
                            </div>
                            
                        </li>
                    @endif
                @endif

                @if($languages)
                    @if(count($languages)>1)
                        <li class="list-group-item p-0">
                            <div class="form-floating ">
                                
                                <select class="form-select border-0" id="language-select" name="language">
                                    @foreach($languages as $language)
                                        <option value="{{$language}}" {{ ( ($report_parameters['language'] ?? null)  == $language) ? 'selected':''}} >{{$language}}</option>
                                    @endforeach
                                </select>
                                <label for="language-select" >Language</small>
                                
                            </div>
                            
                        </li>
                    @endif
                @endif
                @if($parameters)
                    @foreach($parameters as $parameter_name=>$parameter)
                        @includeFirst(['tgn-reports::_report_parameter-'.$parameter["type"], 'tgn-reports::_report_parameter-text'])
                    @endforeach
                 @endif
            </ul>
        </div>
    </div>
</form>
