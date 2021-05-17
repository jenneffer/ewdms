<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\Category;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\Redirect;

class DocumentsController extends Controller
{
  public function __construct()
  {
    return $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {  
      if (auth()->user()->hasRole('Admin')) {
        // get all category
        $category = DB::table('category')->where('parent_id',0)->get();
        
      } else {
        $user_role_id = auth()->user()->role;      
        $arr_category = $this->getCategoryPermissions($user_role_id); //category id yang user boleh access
        $permisible_parent_access = array_filter(array_unique($arr_category),'strlen'); //filter duplicate value and remove null value in the array
        //get the parent category yang user tu boleh access saja
        $category = DB::table('category')->where('parent_id',0)->whereIn('id', $permisible_parent_access)->get();
      }
    
      return view('documents.category', compact('category'));
  }

  // my documents
  public function mydocuments()
  {
    // get user's docs
    $user_id = auth()->user()->id;

    $docs = Document::where('user_id', $user_id)->get();

    return view('documents.mydocuments', compact('docs'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $categories = Category::pluck('name', 'id')->all();
    $categories_name = DB::table("category")->where("parent_id", 0)->pluck('name', 'id');
    return view('documents.create', compact('categories', 'categories_name'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|string|max:255',
      'description' => 'required|string|max:255',
      //'file' => 'max:50000',
    ]);

    // get the data of uploaded user
    $user_id = auth()->user()->id;
    $department_id = auth()->user()->department_id;

    // handle file upload
    /*if ($request->hasFile('file')) {
            // filename with extension
            $fileNameWithExt = $request->file('file')->getClientOriginalName();
            // filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // extension
            $extension = $request->file('file')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // upload file
            $path = $request->file('file')->storeAs('public/files/'.$user_id, $fileNameToStore);
        }
        */
    $doc = new Document;
    $doc->name = $request->input('name');
    $doc->url = $request->input('url');
    $doc->description = $request->input('description');
    $doc->user_id = $user_id;
    $doc->department_id = !empty($department_id) ? $department_id : 0;
    // $category_id = (!empty($request->input('child_id'))) ? $request->input('child_id') : $request->input('parent_id');
    if(!empty($request->input('further_child_id'))){
      $category_id = $request->input('further_child_id');      
    }
    elseif(!empty($request->input('child_id'))){
      $category_id = $request->input('child_id');   
    }
    else{
      $category_id = $request->input('parent_id');
    }
    
    $doc->category_id = $category_id;
    /*$doc->file = $path;
        $doc->mimetype = Storage::mimeType($path);
        $size = Storage::size($path);
        if ($size >= 1000000) {
          $doc->filesize = round($size/1000000) . 'MB';
        }elseif ($size >= 1000) {
          $doc->filesize = round($size/1000) . 'KB';
        }else {
          $doc->filesize = $size;
        }*/

    // determine whether it expires
    if ($request->input('isExpire') == true) {
      $doc->isExpire = false;
    } else {
      $doc->isExpire = true;
      $doc->expires_at = $request->input('expires_at');
    }
    // save to db
    $doc->save();
    // add Category
    $doc->categories()->sync($category_id);

    \Log::addToLog('New Document, ' . $request->input('name') . ' was uploaded');

    return redirect('/documents')->with('success', 'File Uploaded');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //get the category_id    
    $doc = Document::findOrFail($id);
    $category_id=$doc->category_id;
    $cat= Category::findorFail($category_id);
    $parent_id = $cat->parent_id;
    $child_id = $cat->child_id;
    
    return view('documents.show', compact('doc','parent_id','child_id','category_id','id'));

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $doc = Document::findOrFail($id);
    $category_id=$doc->category_id;
    $cat= Category::findorFail($category_id);
    $parent_id=$cat->parent_id;
    $p_cat_name = Category::where("parent_id", 0)->pluck('name', 'id');
    // $parent_name=Category::findName($parent_id);
    
    $sub_cat_name=Category::where("parent_id", $parent_id)->where("child_id",0)->pluck('name', 'id');
    $child_list=Category::where("child_id",$cat->child_id)->pluck('name', 'id');
    $child_id = $cat->child_id;
   

    return view('documents.edit', compact('doc','sub_cat_name','parent_id','category_id','p_cat_name','cat','child_list','child_id'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required|string|max:255',
      'description' => 'required|string|max:255'
    ]);

    $doc = Document::findOrFail($id);
    $doc->name = $request->input('name');
    $doc->description = $request->input('description');
   
    if(!empty($request->input('child_id'))){
      $category_id=$request->input('child_id');
    }else{
      $category_id = (!empty($request->input('subcat_id'))) ? $request->input('subcat_id') : $request->input('parent_id');
    }

    $doc->category_id=$category_id;
    // determine whether it expires
    if ($request->input('isExpire') == true) {
      $doc->isExpire = false;
      $doc->expires_at = null;
    } else {
      $doc->isExpire = true;
      $doc->expires_at = $request->input('expires_at');
    }
    $doc->save();
    $doc->categories()->sync($category_id);
    \Log::addToLog('Document ID ' . $id . ' was edited');

    return redirect('/documents')->with('success', 'Successfully Updated!');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $doc = Document::findOrFail($id);
    // delete the file on disk
    Storage::delete($doc->file);
    // delete db record
    $doc->delete();
    // delete associated categories
    $doc->categories()->detach();

    \Log::addToLog('Document ID ' . $id . ' was deleted');

    return redirect('/documents')->with('success', 'Deleted!');
  }

  // delete multiple docs selected
  public function deleteMulti(Request $request)
  {
    $ids = $request->ids;
    DB::table('document')->whereIn('id', explode(',', $ids))->delete();

    \Log::addToLog('Selected Documents Are Deleted! '. $ids);

    // return redirect()->back()->with('success', 'Selected Documents Deleted!');
    return response()->json(['success' => true, 'msg'=>'Documents '.$ids.' Deleted!']);
  }

  // opening file
  public function open($id)
  {
    $doc = Document::findOrFail($id);
    // $path = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($doc->file);
    // $type = $doc->mimetype;

    \Log::addToLog('Document ID ' . $id . ' was viewed');

    //open url in new tab
    return Redirect::to($doc->url);


  //   if (
  //     $type == 'application/pdf' || $type == 'image/jpeg' ||
  //     $type == 'image/png' || $type == 'image/jpg' || $type == 'image/gif'
  //   ) {
  //     return response()->file($path, ['Content-Type' => $type]);
  //   } elseif (
  //     $type == 'video/mp4' || $type == 'audio/mpeg' ||
  //     $type == 'audio/mp3' || $type == 'audio/x-m4a'
  //   ) {
  //     return view('documents.play', compact('doc'));
  //   } else {
  //     return response()->file($path, ['Content-Type' => $type]);
  //   }
   }

  // download file
  public function download($id)
  {
    $doc = Document::findOrFail($id);
    $image = file_get_contents("https://nxg.box.com/s/0l2dj77jkbaz6ve6g0xnjp7x3qbjwstb");


    file_put_contents(public_path('images\mobile.txt'), $image);

    // $path = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($doc->file);

    // $type = $doc->mimetype;

    // \Log::addToLog('Document ID ' . $id . ' was downloaded');

    // return response()->download($path, $doc->name, ['Content-Type:' . $type]);
    // return response()->download($path);
  }

  // searching
  public function search(Request $request)
  {
    $this->validate($request, [
      'search' => 'required|string'
    ]);

    $srch = strtolower($request->input('search'));
    $names = Document::pluck('name')->all();
    $results = [];

    for ($i = 0; $i < count($names); $i++) {
      $lower = strtolower($names[$i]);
      if (strpos($lower, $srch) !== false) {
        $results[$i] = Document::where('name', $names[$i])->get();
      }
    }

    return view('documents.results', compact('results'));
  }

  // sorting
  public function sort(Request $request)
  {
    $filetype = $request->input('filetype');

    $docs = Document::where('mimetype', $filetype)->get();

    return view('documents.index', compact('docs', 'filetype'));
  }

  public function trash()
  {
    // make expired documents
    $docs = Document::where('isExpire', 1)->get();
    $today = Date('Y-m-d');

    foreach ($docs as $d) {
      if ($today > $d->expires_at) {
        $maketrash = Document::findOrFail($d->id);
        $maketrash->isExpire = 2;
        $maketrash->save();
      }
    }
    // find out auth user role
    $user = auth()->user();
    // find trashed documents
    if ($user->hasRole('Admin')) {
      $trash = Document::where('isExpire', 2)->get();
    } elseif ($user->hasRole('Moderator')) {
      $trash = Document::where('isExpire', 2)->where('department_id', $user->department_id)->get();
    } else {
      $trash = Document::where('isExpire', 2)->where('user_id', $user->id)->get();
    }

    return view('documents.trash', compact('trash'));
  }

  public function restore($id)
  {
    $restoreDoc = Document::findOrFail($id);
    $restoreDoc->isExpire = 0;
    $restoreDoc->expires_at = null;
    $restoreDoc->save();

    return redirect()->back()->with('success', 'Successfully Restored!');
  }

  //check category id punya parent_id

  public function showCategoryItem($id){
      $arr_cat_same_parent = $this->arr_cat_from_same_parent($id);
      if (auth()->user()->hasRole('Admin')) {
        // get all
        $docs = Document::where('isExpire', '!=', 2)->whereIn('category_id', $arr_cat_same_parent)->get();
        
      } else {
        $user_role_id = auth()->user()->role;    
        
        $arr_category = $this->getCategoryPermissions($user_role_id); //category id yang user boleh access    
        //category id $id or parent_id is $id
        // $docs = DB::table('document')->where('user_id', '!=', auth()->user()->id)->whereIn('category_id',array_filter(array_unique($arr_category),'strlen'))->get();
        // $docs = DB::table('document')->where('user_id', '!=', auth()->user()->id)->where('category_id',$id)->get();
        $docs = DB::table('document')->where('category_id',$id)->get();
        
      }
      //find parent_id for breadcrumb purpose
      $parent_id = Category::findParentId($id);
      //find subcatid
      $child_id = Category::findSubCatId($id);
      $filetype = null;
      return view('documents.index', compact('docs', 'filetype','id','parent_id','child_id'));
  }

  public function showSubCategoryItem($id){
    //get the sub category from table category
    if(auth()->user()->hasRole('Admin')){
      $subcategory = DB::table('category')->where('parent_id',$id)->where('child_id',0)->get();
      //check if there's file under that particular category
      $docs = Document::where('category_id', $id)->get();
    }
    else{
      $user_role_id = auth()->user()->role;           
      $arr_category = $this->getCategoryPermissions($user_role_id); //category id yang user boleh access
      
      $permisible_parent_access = array_filter(array_unique($arr_category),'strlen'); //filter duplicate value and remove null value in the array
      //get the parent category yang user tu boleh access saja
      $subcategory = DB::table('category')->where('parent_id',$id)->where('child_id',0)->whereIn('id', $permisible_parent_access)->get();
      $docs = Document::where('category_id', $id)->get();
    }

    return view('documents.subcategory', compact('subcategory','docs','id'));
  }

  public function showSubCategoryChildItem( $parent_id, $id ){    
    if(auth()->user()->hasRole('Admin')){
      $subcategory = DB::table('category')->where('parent_id',$parent_id)->where('child_id',$id)->get();

      $docs = Document::where('category_id', $id)->get();
    }
    else{
      $user_role_id = auth()->user()->role;           
      $arr_category = $this->getCategoryPermissions($user_role_id); //category id yang user boleh access
      
      $permisible_parent_access = array_filter(array_unique($arr_category),'strlen'); //filter duplicate value and remove null value in the array
      //get the parent category yang user tu boleh access saja
     
      $subcategory = DB::table('category')->where('parent_id',$parent_id)->where('child_id',$id)->get(); 
      $docs = Document::where('category_id', $id)->get();

    }

    return view('documents.subcategory-item', compact('subcategory','docs','parent_id','id'));
  }
  public function getCategoryPermissions($user_role_id){    
    //get role's permissions      
    $permissions = DB::table('role_has_permissions')->where('role_id','=', $user_role_id)->get();      
    $permissionIDs = [];
    foreach($permissions as $p){
      $permissionIDs[] = $p->permission_id;
    }
    //check the permissions punya category
    $categories = DB::table('permissions')->whereIn('id',$permissionIDs)->get();
    $arr_category = [];      
    foreach($categories as $c){
        $arr_category[] = $c->category_id;
    }
    return $arr_category;
  }

  public function arr_cat_from_same_parent($id){
    $result = DB::table('category')->where('parent_id', $id)->orWhere('id', $id)->get();
    $IDs = [];
    foreach($result as $res){   
     
      $IDs[] = $res->id;
    }
    return $IDs;
  }

  public function viewEmbedLink($id){
    $doc = DB::table('document')
              ->join('category','document.category_id','=','category.id')->where('document.id', $id)->first();

    return view('documents.view-embed', compact('doc'));
  }
}
