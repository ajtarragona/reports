
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


<body class="template-clean margin-{{ $margin ?? 'lg' }} size-{{ $pagesize ?? 'A4' }} orientation-{{ $orientation ?? 'portrait' }}">
	
	@yield('body')
	

</body>
