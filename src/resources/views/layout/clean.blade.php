
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<style>
		@php 
			include public_path('vendor/ajtarragona/css/reports-print.css');
			
		@endphp
		@yield('css')

	</style>

</head>


<body class="template-master margin-{{ $margin ?? 'lg' }} size-{{ $pagesize ?? 'A4' }} orientation-{{ $orientation ?? 'portrait' }}">
	
	<main>
		@yield('body')
	</main>
	

</body>
