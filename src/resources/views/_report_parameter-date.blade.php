<li class="list-group-item p-0 ">
    <div class="form-floating ">
        <input type="date" name="{{$name}}" value="{{$value ?? old($name)}}" class="form-control border-0" id="parameter-{{$name}}" placeholder="{{$label}}">
        <label for="parameter-{{$name}}">{{$label}}</label>
    </div>
</li>