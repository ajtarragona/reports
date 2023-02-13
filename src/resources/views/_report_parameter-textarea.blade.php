<li class="list-group-item p-0 ">
                                
    <div class="form-floating ">
        <textarea class="form-control border-0" placeholder="{{$parameter["label"]}}" name="{{$parameter_name}}" id="parameter-{{$parameter_name}}" style="height: 100px">{{$report_parameters[$parameter_name] ?? old($parameter_name)}}</textarea>
        <label for="parameter-{{$parameter_name}}">{{$parameter["label"]}}</label>
    </div>
</li>