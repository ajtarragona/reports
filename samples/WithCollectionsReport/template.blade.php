@extends('tgn-reports::layout/master-tarragona')

@section('title')
	With Collections
@endsection

@section('subtitle')
	Subtitle here
@endsection

@section('body')

	<div class="page">
		START HERE
		<p>{!! $param1 !!}</p>
		
		lll/{!! $siglas  !!}
        @if($departament) 
			<br>
			Departament de <strong>{!! $departament  !!}</strong>
        @endif
        <br><br>
        <p class="text-justify">
            Un cop havent-se realitzat el tràmit corresponent o transcorregut el termini
            establert, procedim a la devolució dels següents expedients:
        </p>

		<h2>Cosas</h2>
		<table class="table table-display table-striped table-compact  text-sm fullwidth">
			<thead>
				<tr>
					<th><div>Col1</div></th>
					<th><div>Col2</div></th>
				</tr>
			</thead>
			<tbody>
				@foreach($cosas as $cosa)
					<tr>
						<td><div>{!! $cosa["col1"] !!}</div></td>
						<td><div>{!! $cosa["col2"] !!}</div></td>
					</tr>
				@endforeach
			</tbody>
		</table>
		
    </div>

@endsection

@section('footer')
    FOOTER HERE
@endsection