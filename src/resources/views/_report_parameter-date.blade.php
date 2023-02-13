<li class="list-group-item p-0 ">
    <div class="form-floating ">
        <input type="date" name="{{$parameter_name}}" value="{{$report_parameters[$parameter_name] ?? old($parameter_name)}}" class="form-control border-0" id="parameter-{{$parameter_name}}" placeholder="{{$parameter["label"]}}">
        <label for="parameter-{{$parameter_name}}">{{$parameter["label"]}}</label>
    </div>
</li>