<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reports</title>

        <link href="{{ asset('vendor/ajtarragona/css/reports.css') }}" rel="stylesheet">
        {{-- <link href="{{ asset('vendor/ajtarragona/css/reports-print.css') }}" rel="stylesheet"> --}}
        
        

    </head>
    <body class="vh-100 overflow-hidden">
        <div class="vh-100">

            <div class="row g-0 vh-100">
                <div class="col-sm-2   " >
                    <div class="vh-100 border-end" style="overflow-y: auto">
                        
                        @if($reports->isNotEmpty())
                            <div class="list-group list-group-flush mt-3 ">
                            @foreach($reports  as $report)
                                <a href="{{ route('tgn-reports.home', $report_name == $report->short_name ? '' : $report->short_name) }}" class="d-flex justify-content-between list-group-item list-group-item-action {{$report_name == $report->short_name ? 'active': ''}} ">
                                    <span>
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        {{ $report->name }}
                                    </span>

                                    @if($report_name == $report->short_name)
                                    <i class="bi bi-x-circle"></i>
                                        
                                    @endif

                                    {{-- {{ $report->getClassPath() }}<br>
                                    {{ $report->getPath() }} --}}
                                </a>
                            @endforeach
                            </div>
                        @else
                         <div class="text-muted p-3">
                            Encara no hi ha cap report creat
                         </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-10 h-100 bg-secondary  bg-opacity-10">
                    <div class="h-100 ">
                        @if($report_name)
                            
                            @include('tgn-reports::_report_single',['report'=>$current_report])
                            
                        @else
                            @include('tgn-reports::docs')
                            
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('vendor/ajtarragona/js/reports.js')}}" language="JavaScript"></script>
        
    </body>

</html>
