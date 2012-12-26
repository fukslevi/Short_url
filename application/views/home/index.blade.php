@layout('master')

@section('container')
	<h1>My Awesome URL</h1>
	{{ Form::open('/') }}
		{{ Form::text('url') }}
<!-- 		{{ Form::submit('Shorten') }} -->
	{{ Form::close() }}

	{{ $errors->first('url', '<p class=error>:message</p>') }}
@endsection