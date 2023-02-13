<li class="list-group-item ">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" value="1" name="{{$parameter_name}}" {{ ($report_parameters[$parameter_name] ?? false) ? 'checked' : '' }} role="switch" id="parameter-{{$parameter_name}}" >
        <label class="form-check-label" for="parameter-{{$parameter_name}}">{{$parameter["label"]}}</label>
    </div>
</li>