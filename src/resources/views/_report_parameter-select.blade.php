<li class="list-group-item p-0 ">
                                    
    <div class="form-floating ">
        <select class="form-select border-0 {{$class??''}}" name="{{$name}}" id="parameter-{{$name}}">
            @foreach($options as $key=>$val)
                <option value="{{$key}}" {{ ( $value??null  == $key) ? 'selected':''}} >{{$val}}</option>
            @endforeach
        </select>
        <label for="parameter-{{$name}}" >{{ $label }}</label>
    </div>
</li>