<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reports</title>

        <link href="{{ asset('vendor/ajtarragona/css/reports.css') }}" rel="stylesheet">

    </head>
    <body class="bg-secondary h-100">
        <div class="d-flex h-100 w-100 align-items-center justify-content-center">
            <div class="card">
                <form method="post" action="{{ route('tgn-reports.login')}}">
                    @csrf
                    <div class="card-body p-5 text-center">
                        <h3 class="mb-3">TGN-Reports</h3>
                        <div class="form-floating ">
                            <input type="password" name="password" value="" class="form-control" id="reports-password" placeholder="Enter password...">
                            <label for="reports-password">Password</label>
                        </div>
                        @if($errors->has('password'))
                        <div class="text-danger text-center mt-3">
                            {{ $errors->first('password')}}
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        
    </body>
</html>
