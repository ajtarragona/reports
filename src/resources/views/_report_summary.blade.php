<ul class="list-group">
    <li class="list-group-item p-4 ">
        <div class="d-flex justify-content-between align-items-start">
            <h5 class="mb-1"><i class="bi bi-file-earmark-pdf"></i> {{$report->name()}}</h5>
            <div>
                
                <form method="post" action="{{route('tgn-reports.export',$report->short_name)}}">
                    @csrf
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-download"></i> Download</button>
                </form>
            </div>
        </div>
        @if($description=$report->description())
            <small class="text-sm text-muted mt-3 d-block">{!! $report->description() !!}</small>
        @endif
    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Miniatura</small>
        <div class="ms-2">
            @if($report->hasThumbnail()) 
                <div class="report-thumbnail mb-2">
                    {!! $report->renderThumbnail() !!}
                </div>
            @endif
            <form method="post" action="{{route('tgn-reports.generateThumbnail',$report->short_name)}}">
                @csrf
                <button class="btn btn-sm btn-light " ><i class="bi bi-image"></i> {{$report->hasThumbnail()?'Regenerar':'Generar'}}</button>
            </form>
        </div>

    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Nom</small>
        <code>{{$report->short_name}}</code>
    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Múltiple</small>
        <span class="badge bg-{{ $report->multiple ?'success':'warning' }}">{{ $report->multiple ?'SI':'NO' }}</span>
    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Pagination</small>
        <span class="badge bg-{{ $report->pagination ?'success':'warning' }} ">{{ $report->pagination ?'SI':'NO' }}</span>
    </li>
    <li class="list-group-item d-flex align-items-start">
        <small class="text-muted  w-50 ">Margin</small>
        <span class="badge bg-light text-dark">{{ strtoupper($report->margin)  }}</span>
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
