
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<style>
		@php 
			include public_path('vendor/ajtarragona/css/reports-print.css');
			
		@endphp
		@yield('css')
		
	</style>
	@yield('head')

</head>


<body class="template-master margin-{{ $margin ?? 'lg' }} size-{{ $pagesize ?? 'A4' }} orientation-{{ $orientation ?? 'portrait' }}">
	
	<header >
		<table class="fullwidth">
			<tbody>
				<td class="text-left">
					<h4>@yield('title')</h4>
					<h5 class="subtitle">@yield('subtitle')</h5>
				</td>
				</tr>
			</tbody>
		</table>
	</header>

	
	<footer>
		<table class="fullwidth">
			<tbody>
				<tr>
					<td>
						<div class="content">
							@yield('footer') 
						</div>
					</td>
					<td class=" text-right" >
						@if(isset($pagination) && $pagination)
							@include('tgn-reports::layout._pagination')
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	</footer>
	
	<main>
		@yield('body')
	</main>
	

</body>
