<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',function() {
    // display index page
    if (Auth::check()) {
      return view('/dashboard');     
    }
    return view('auth.login');
});

//
Auth::routes();

// first time login
Route::get('first_time_login', function() {
  return view('/first_time_login');
})->middleware('auth');


//download NDA form, approve etc
Route::resource('nda','NDAController');
Route::get('/nda/reject/{id}', 'NDAController@reject')->name('nda.reject');
Route::get('/downloadNDA', 'NDAController@downloadNDA');
Route::get('/nda/view_nda/{file_name}', 'NDAController@viewNda')->name('nda.view_pdf');

// dashboard
Route::get('dashboard', function() {
  return view('/dashboard');
})->middleware('auth');


// users
Route::resource('users','UsersController');
Route::post('users/deactivate','UsersController@deactivate')->name('user.deactivate');
// departments
Route::resource('departments','DepartmentsController');

// categories
Route::resource('categories','CategoriesController');
Route::delete('categoriesDeleteMulti', 'CategoriesController@deleteMulti');
Route::get('getSubCat/{id}','CategoriesController@getSubCat')->name('getSubCat');
Route::get('getChildCat/{id}','CategoriesController@getChildCat')->name('getChildCat');

// //test
// Route::get('dropdown','CategoriesController@state');
// Route::get('city/{id}','CategoriesController@city');


//sub categories
// Route::resource('categories/{id}/addSub','SubCategoryController');
Route::resource('categories/{id}/showSub','SubCategoryController');
Route::get('categories/{id}/showSub','SubCategoryController@index')->name('subcat.index');
Route::delete('subcategoriesDeleteMulti', 'SubCategoryController@deleteMulti');
Route::post('categories/addSub','SubCategoryController@storeSubCategoryItem')->name('subcat.store.item');



// documents
Route::resource('documents','DocumentsController');
Route::get('documents', 'DocumentsController@index')->name('category');
Route::get('documents/download/{id}','DocumentsController@download');
Route::get('documents/open/{id}','DocumentsController@open')->name('documents.open');
Route::get('mydocuments','DocumentsController@mydocuments');
Route::get('/trash','DocumentsController@trash');
Route::get('documents/restore/{id}','DocumentsController@restore');
Route::delete('documentsDeleteMulti','DocumentsController@deleteMulti');

//open embed link
Route::get('documents/{id}/viewEmbedLink','DocumentsController@viewEmbedLink')->name('view.embed');

//test-jen
Route::get('documents/category/{id}','DocumentsController@showCategoryItem')->name('documents.category');
Route::get('documents/category/{id}/subcategory','DocumentsController@showSubCategoryItem')->name('documents.subcategory');
Route::get('documents/category/{parent_id}/subcategory/{id}','DocumentsController@showSubCategoryChildItem')->name('documents.subcategory.item');

// search
Route::post('/search','DocumentsController@search');
// sort
Route::post('/sort', 'DocumentsController@sort');
// shared
Route::resource('shared','ShareController');
// roles 
Route::resource('roles','RolesController');
Route::get('roles', 'RolesController@index')->name('roles.index');
//permissions
Route::resource('permissions','PermissionsController');
// profile
Route::resource('profile','ProfileController');
Route::patch('profile','ProfileController@changePassword');
// registeration requests
Route::resource('requests','RequestsController');

// backup
Route::get('backup','BackupController@index');
Route::get('backup/create','BackupController@create');
Route::get('backup/download','BackupController@download');
Route::get('backup/delete','BackupController@delete');
// log
Route::get('logs','LogController@log');
Route::get('logsdel','LogController@logdel');

//enquiries
Route::resource('enquiries','EnquiriesController');

// Email related routes
// Route::get('mail/send', 'MailController@send');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
