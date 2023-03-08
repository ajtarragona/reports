<h1>{!! $title !!}</h1>

<table class="table table-striped table-compact  text-sm fullwidth">
    <thead>
        <tr>
            @if($columns)
                @foreach($columns as $column_key=>$column_label)
                    <th><div>{{  $column_label  }}<div></th>
                @endforeach
            @endif
        
        </tr>
    </thead>

    <tbody>