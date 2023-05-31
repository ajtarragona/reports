{{-- @dump($parameter) --}}
@if(isset($parameter["columns"]))
    <li class="list-group-item ">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="w-50 text-muted"><small>{{$label}}</small></div>
            <input type="number" name="{{$name}}[num_rows]" value="15" class="form-control border-0 text-end" id="parameter-{{$name}}-numrows" placeholder="Files">

            
        </div>
        <ul class="list-group list-group-flush">
            @foreach($parameter["columns"] as $column_key=>$column)
                @includeFirst(['tgn-reports::_report_parameter-'.($column["type"]??'text'), 'tgn-reports::_report_parameter-text'],[
                    'name'=>$name.'[columns]['.$column_key.']',
                    'options'=> $column["options"] ?? null,
                    'value'=>$column["default_value"]??null,
                    'label'=> $column["label"],
                    'class'=>'form-control-sm'
                ])
                
                
            @endforeach
        </ul>
    </li>

    {{-- @dump($report->getColumns()) --}}

    
@endif
