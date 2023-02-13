<li class="list-group-item ">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" value="1" name="{{$name}}" {{ ($value ?? false) ? 'checked' : '' }} role="switch" id="parameter-{{$name}}" >
        <label class="form-check-label" for="parameter-{{$name}}">{{$label}}</label>
    </div>
</li>