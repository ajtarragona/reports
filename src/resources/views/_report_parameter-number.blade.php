<li class="list-group-item p-0 ">
    <div class="form-floating ">
        <input type="text" step="any" name="{{$name}}" value="{{$value ?? old($name)}}" class="form-control border-0 {{$class??''}}" id="parameter-{{$name}}" placeholder="{{$label}}">
        <label for="parameter-{{$name}}">{{$label}}</label>
    </div>
</li>