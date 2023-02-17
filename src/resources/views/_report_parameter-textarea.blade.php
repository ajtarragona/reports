<li class="list-group-item p-0 ">
                                
    <div class="form-floating ">
        <textarea class="form-control border-0 {{$class??''}}" placeholder="{{$label}}" name="{{$name}}" id="parameter-{{$name}}" style="height: 100px">{{$value ?? old($name)}}</textarea>
        <label for="parameter-{{$name}}">{{$label}}</label>
    </div>
</li>