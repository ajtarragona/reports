<tr>
	@if($row)
		@foreach($row as $column_key=>$column_value)
			<td>
				<div>
					{!! $column_value !!} 	
				</div>
			</td>
		@endforeach			
	@endif
</tr>