@if(Session::has('toasts'))
	<!-- Messenger http://github.hubspot.com/messenger/ -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

	<script type="text/javascript">
		toastr.options = {
			"closeButton": true,
			"newestOnTop": false,
			"positionClass": "toast-top-right",
			"progressBar": true,
		};

		@foreach(Session::get('toasts') as $toast)
			toastr["{!! $toast['level'] !!}"]("{!! $toast['message'] !!}","{!! $toast['title'] !!}");
		@endforeach
	</script>
@endif
