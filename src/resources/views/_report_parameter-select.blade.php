<li class="list-group-item p-0 ">
                                    
    <div class="form-floating ">
        <select class="form-select border-0" name="{{$parameter_name}}" id="parameter-{{$parameter_name}}">
            @foreach($parameter["options"] as $key=>$value)
                <option value="{{$key}}" {{ ( ($report_parameters[$parameter_name] ?? null)  == $key) ? 'selected':''}} >{{$value}}</option>
            @endforeach
        </select>
        <label for="language-select" >{{$parameter["label"]}}</small>
    </div>
</li>