<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'Spanz')</title>
	<link rel="stylesheet" href="{{ asset('css/output.css') }}?v={{ file_exists(public_path('css/output.css')) ? filemtime(public_path('css/output.css')) : time() }}">
</head>
<body>
	<div class="bg-image bg-cover bg-center bg-no-repeat flex flex-col " style="background-image: url('{{ asset('spanz-img/spanz-bg.jpg') }}');">
		@yield('content')
	</div>
</body>
</html>