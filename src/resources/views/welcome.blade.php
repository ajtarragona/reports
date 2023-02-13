<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reports</title>

        {{-- <link href="{{ asset('vendor/ajtarragona/css/reports.css') }}" rel="stylesheet"> --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">


    </head>
    <body class="h-100 overflow-hidden">
        <div class="h-100">

            <div class="row g-0 h-100">
                <div class="col-sm-2   " >
                    <div class="h-100 border-end" style="overflow-y: auto">
                        
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
                    <div class="h-100">
                        @if($report_name)
                            
                            @include('tgn-reports::_report_single',['report'=>$current_report])
                            
                        @else
                            <div class="p-3">
                                <p>Pots crear nous reports amb la comanda Artisan:</p>
                                <p class="bg-dark p-3 rounded">
                                    <code >
                                    php artisan make:censat-report {report_name}
                                    </code>
                                </p>
                                <p>En trobaràs el codi font a la ruta <code>storage/app/reports</code></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- <script src="{{ asset('vendor/ajtarragona/js/reports.js')}}" language="JavaScript"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>

</html>
