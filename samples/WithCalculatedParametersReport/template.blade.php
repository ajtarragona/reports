@extends('tgn-reports::layout/master-tarragona')

@section('title')
	With Calculated Parameters
@endsection

@section('body')

	<div class="page">
		{!!$param1!!} + {!!$param2!!} = {!!$suma!!}

				
	<table class="table mt-3 mb-3 fullwidth" >
		<thead>
			<tr>
				<th class="text-left"><div>NUM1</div></th>
				<th class="text-left"><div>NUM2</div></th>
				<th class="text-left"><div>SUMA</div></th>
				{{-- <th class="text-left"><div>NÚM.REGISTRE SANITARI</div></th>
				<th class="text-left"><div>Descripció</div></th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($registres as $registre)
				<tr>
					<td>
						<div>{!!$registre["caca1"]!!}</div>
					</td>
					<td>
						<div>{!!$registre["caca2"]!!}</div>
					</td>
					<td>
						<div>{!!$registre["subsuma"]!!}</div>
					</td>
					{{-- <td><div>{!! $registre["numregsanitari"] !!}</div></td>
					<td><div>{!! $registre["nom_activitat"]  !!}</div></td> --}}
				</tr>
			@endforeach
		</tbody>
	</table>
    </div>

@endsection

