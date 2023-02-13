<ul class="list-group">
    <li class="list-group-item p-4">
        <h5><i class="bi bi-file-earmark-pdf"></i> {{$report->name}}</h5>
    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Nom</small>
        <code>{{$report->short_name}}</code>
    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Múltiple</small>
        <span class="badge bg-light text-dark">{{ $report->config('multiple',false) ?'SI':'NO' }}</span>
    </li>
    @if($pagesizes=$report->getPagesizes())
       
        <li class="list-group-item d-flex align-items-start">
            <small class="text-muted  w-50 ">Pagesizes</small>
            <span>
                 @foreach($pagesizes as $pagesize)
                    <span class="badge bg-light text-dark">{{$pagesize}}</span>
                @endforeach
            </span>
        </li>
       
    @endif
    @if($orientations=$report->getOrientations())
        <li class="list-group-item d-flex align-items-start">
            <small class="text-muted  w-50 ">Orientations</small>
            <span>
                @foreach($orientations as $orientation)
                <span class="badge bg-light text-dark">{{$orientation}}</span>

                @endforeach
            </span>
        </li>
    @endif
    @if($languages=$report->getLanguages())
        <li class="list-group-item d-flex align-items-start">
            <small class="text-muted  w-50 ">Languages</small>
            <span>
                @foreach($languages as $language)
                <span class="badge bg-light text-dark">{{$language}}</span>

                @endforeach
            </span>
        </li>
    @endif
   

    
</ul>
