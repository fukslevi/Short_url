<?php
Route::get('/', function()
{
	return View::make('home.index');
});


Route::post('/', function(){
	$url = Input::get('url');
	$v = Url::validate(array('url' => $url));
	if ( $v !== true ) {
		return Redirect::to('/')->with_errors($v->errors);
	}

	// If the url is already in the table, return it
	$record = Url::where_url($url)->first();
	if ( $record ) {
		// then return it
		return View::make('home.result')
				->with('shortened', $record->shortened);
	}

	// otherwise, add a new row, and return the shortened url
	$row = Url::create(array(
		'url' => $url,
		'shortened' => Url::get_unique_short_url()
		));
	// TODO FOR ME. What to do if the row was not created?
	if ( $row ) {
		return View::make('home.result')->with('shortened', $row->shortened);
	}
});

Route::get('(:any)', function($shortened){
	// query the DB for the row with tha short url
	$row = Url::where_shortened($shortened)->first();

	// if not found, redirect to homepage
	if( is_null($row) ) return Redirect::to('/');

	// Otherwise, fetch the URL, and redirect to it
	return Redirect::to($row->url);
});
/*

// SELECT * FROM POSTS
Route::get('/', function()
{
	$posts = DB::table('posts')->get();
	dd($posts);

	$posts = DB::table('posts')->where('id',  '=', '2')->only('title');
	return $posts;
	
	$posts = DB::table('posts')->get(array('title as heading'));
	dd($posts);

//dinamic closers
	$posts = DB::table('posts')
				->where('id', '!=', 1)
				->or_where('title', '=', 'My title')
				->get();
	dd($posts);

	$posts = DB::table('posts')
				->where_title_and\or_body('First title', 'This the the body')
				->get();
	dd($posts);

	$posts = DB::table('posts')
	->where(function($query){
		$query->where('username', '=', $username);
		$query->where('password', '=', $password);
	})
	->get();
	dd($posts);

	$posts = DB::table('posts')
			->order_by('title', 'asc')
			->take(2)
			->get();
	dd($posts);

	// Laravel’s ORM: Eloquent - first
	
	first - create a model name - user.php
	second:
	$users = User::all();
	return View::make('home.index')->with('users', $users);
	dd($users);
	lastly - call it from the view

	$email = 'ori@sosimple.co.il';
	$password = '1234';
	
	// Laravel’s ORM: Eloquent - second

	$user = User::where_email_and_password($email, $password)->first();

	dd($user);

	// Laravel’s ORM: Eloquent - third
	
	$user = new User;
	$user->email = 'fukslevi@gmail.com';
	$user->password = Hash::make('1234');
	$user->save();

	// Laravel’s ORM: Eloquent - fourth

	$user = User::create(
		array(
			'email' => 'michal@gmail.com',
			'password' => Hash::make(1234)
		)
	);
	if ($user) return 'Success!';

});

*/

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});