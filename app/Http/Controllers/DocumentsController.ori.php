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
      // get all
      $docs = Document::where('isExpire', '!=', 2)->get();
      
    } else {
      // get user's docs
      // $user_id = auth()->user()->id;

      // $docs = Document::where('user_id',$user_id)->get();

      // get docs in dept
      $dept_id = auth()->user()->department_id;

      // get user role_id
      $user_role_id = auth()->user()->role;
      $arr_category = $this->getCategoryPermissions($user_role_id);
      // $docs = Document::where('isExpire', '!=', 2)->where('department_id', $dept_id)->where('user_id', '!=', auth()->user()->id)->get();
      // $docs = Document::where('isExpire', '!=', 2)->where('user_id', '!=', auth()->user()->id)->get();
      $docs = Document::where('isExpire', '!=', 2)->where('user_id', '!=', auth()->user()->id)->whereIn('category_id',$arr_category)->get();

      
    }    
    $docs->load('category');
    
    //group by category 
    $doc_by_category = [];
    foreach($docs as $doc){
      $doc_by_category[$doc->category_id][] = array(
        'doc_id' => $doc->id,
        'doc_mimetype' => $doc->mimetype,
        'doc_name' => $doc->name,
        'doc_category_name' => $doc->category->name,
      );
    }    
    $filetype = null;
    return view('documents.index', compact('docs', 'filetype','doc_by_category'));
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
    $doc->department_id = $department_id;
    $category_id = (!empty($request->input('child_id'))) ? $request->input('child_id') : $request->input('parent_id');
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
    $doc = Document::findOrFail($id);

    return view('documents.show', compact('doc'));
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

    return view('documents.edit', compact('doc','sub_cat_name','parent_id','category_id','p_cat_name','cat','child_list'));
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

    \Log::addToLog('Selected Documents Are Deleted!');

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
    $path = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($doc->file);
    $type = $doc->mimetype;

    \Log::addToLog('Document ID ' . $id . ' was downloaded');

    // return response()->download($path, $doc->name, ['Content-Type:' . $type]);
    return response()->download($path);
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

  public function showByCategory(){
    var_dump('$test');
    $user_role_id = auth()->user()->role;
    $arr_category = $this->getCategoryPermissions($user_role_id);
    var_dump($arr_category);
    $category = DB::table('category')->where('parent_id',0)->get();
    return view('documents.category',$category);
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

  // public function getPermissionsCategory(){
    
  //   $arr_category = [];
  //   if(auth()->user()->hasPermissionTo('sop_access')){
  //       array_push($arr_category,1);        
  //   }
  //   if(auth()->user()->hasPermissionTo('creative_guide_access')){
  //       array_push($arr_category,2);
  //   }
  //   if(auth()->user()->hasPermissionTo('medical_access')){
  //       array_push($arr_category,3);
  //   }
  //   if(auth()->user()->hasPermissionTo('non_medical_access')){
  //       array_push($arr_category,4);
  //   }
  //   if(auth()->user()->hasPermissionTo('medical_guidelines')){
  //       array_push($arr_category,5);
  //   }
  //   if(auth()->user()->hasPermissionTo('non_medical_guidelines')){
  //       array_push($arr_category,8);
  //   }
  //   if(auth()->user()->hasPermissionTo('medical_flowchart_access')){
  //       array_push($arr_category,6);
  //   }
  //   if(auth()->user()->hasPermissionTo('non_medical_flowchart_access')){
  //       array_push($arr_category,9);
  //   }
  //   if(auth()->user()->hasPermissionTo('medical_sop_form_access')){
  //       array_push($arr_category,7);
  //   }
  //   if(auth()->user()->hasPermissionTo('non_medical_sop_form_access')){
  //       array_push($arr_category,10);
  //   }
  //   if(auth()->user()->hasPermissionTo('creative_guide_access')){
  //       array_push($arr_category,2);
  //   }
  //   if(auth()->user()->hasPermissionTo('general_template_access')){
  //       array_push($arr_category,11);
  //   }
  //   if(auth()->user()->hasPermissionTo('branding_guidelines_access')){
  //       array_push($arr_category,12);
  //   }
  //   if(auth()->user()->hasPermissionTo('letterhead_access')){
  //       array_push($arr_category,13);
  //   }
  //   if(auth()->user()->hasPermissionTo('name_card_access')){
  //       array_push($arr_category,14);
  //   }
  //   if(auth()->user()->hasPermissionTo('uniform_access')){
  //       array_push($arr_category,15);
  //   }
  //   if(auth()->user()->hasPermissionTo('collaterals_access')){
  //       array_push($arr_category,16);
  //   }
  //   if(auth()->user()->hasPermissionTo('images_access')){
  //       array_push($arr_category,17);
  //   }
  //   if(auth()->user()->hasPermissionTo('marketing_access')){
  //       array_push($arr_category,18);
  //   }
  //   if(auth()->user()->hasPermissionTo('product_brochures_access')){
  //       array_push($arr_category,19);
  //   }
  //   if(auth()->user()->hasPermissionTo('poster_access')){
  //       array_push($arr_category,20);
  //   }	
  //   return $arr_category;
  // }
}
