
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<style>
		@php 
			include public_path('vendor/ajtarragona/css/reports-print.css');
			
		@endphp
		@yield('css')

	</style>

</head>


<body class="margin-{{ $margin ?? 'lg' }} size-{{ $pagesize ?? 'A4' }} orientation-{{ $orientation ?? 'portrait' }}">
	<header >
		<table class="fullwidth">
			<tbody>
				<tr>
					<td>
						<img src="{{ public_path('vendor/ajtarragona/images/logos/logo_2x.jpg') }}" width="200"/>
					</td> 
					<td class="text-right">
						<h4>@yield('title')</h4>
						<h5 class="subtitle">@yield('subtitle')</h5>
					</td>
				</tr>
			</tbody>
		</table>
	</header>

	
	<main>
		@yield('body')
	</main>
		
	
	<footer>
		<div class="content">
			@yield('footer') 
		</div>
		
		@if(isset($pagination) && $pagination)
			@include('tgn-reports::layout._pdf-pagination');
		@endif
		
	</footer>

</body>
