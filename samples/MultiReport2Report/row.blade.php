
<td class="col-{{ 12/$numcols }} ">
	<div class="m-1 p-3 bg-{{$color}}-20 border-1 text-center">
		<h1 class="">{!! $loop->index !!}</h1>
		<p>{!! $title !!}</p>
	
		<p class="text-{{$color}} text-sm">{!! $frase !!}</p>
	</div>
</td>

@if($loop->index % $numcols == 0)
</tr>
<tr>
@endif