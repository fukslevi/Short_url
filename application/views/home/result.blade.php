@layout('master')

@section('container')
	<h1>Here is your shortened URL</h1>
	{{ HTML::link($shortened, "local.articlemaster.com/$shortened") }}
@endsection