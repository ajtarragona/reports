<li class="list-group-item p-0 ">
                                    
    <div class="form-floating ">
        <select class="form-select border-0" name="{{$name}}" id="parameter-{{$name}}">
            @foreach($options as $key=>$val)
                <option value="{{$key}}" {{ ( $value  == $key) ? 'selected':''}} >{{$val}}</option>
            @endforeach
        </select>
        <label for="parameter-{{$name}}" >{{ $label }}</label>
    </div>
</li>