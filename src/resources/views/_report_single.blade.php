    <div class="row g-0 h-100">
        <div class="col-sm-3 h-100" >
            <div class="p-3 h-100 overflow-auto" >
                @include('tgn-reports::_report_summary',['report'=>$report])
                @include('tgn-reports::_report_parameters', ['report'=>$report])
            
            </div>
        </div>
        <div class="col-sm-9 h-100">
            {{-- <div class="border bg-secondary p-4 h-100 overflow-auto">
                <div class="text-end mb-3 ">
                    <div class="actions btn-group" >
                        <form method="post" action="{{ route('generate',$report->short_name)}}"> 
                            @csrf
                            <button class="btn btn-primary" type="submit"><i class="bi bi-file-pdf"></i> Generar PDF</button>
                        </form>
                    </div>
                </div> --}}

                <iframe class="bg-secondary shadow h-100 w-100" id="report-preview" name="report-preview">
                    
                </iframe>
            {{-- </div> --}}
        </div>
    </div>
