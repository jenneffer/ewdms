<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Redirect;
use App\SubCategory;
use App\Category;
use DB;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth', 'permission:manage']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $subcategories = Category::where('parent_id', $id)->where('child_id',0)->get();      
        //construct array category-sub category-subcategoryitem  
        $cat_name= Category::findName($id);
        $subcatitem = [];
        foreach($subcategories as $sub){
            $subcatitem[$sub->id][] = DB::table("category")->where('child_id',$sub->id)->get();
        }
        return view('subcat.index', compact('subcategories','id','cat_name','subcatitem'));
        //return view('subcat.index', compact('subcategories','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        //$cate = SubCategory::create([$request->input('name')]);
        // $cate = new SubCategory;
        // $cate->name = $request->input('name');
        // $cate->parent_cat = $id;
        // $cate->created_by = auth()->user()->id;
        // $cate->save();
        $cate = new Category;
        $cate->name = $request->input('name');
        $cate->parent_id = $id;
        $cate->child_id = 0;
        $cate->created_by = auth()->user()->id;
        $cate->save();
        \Log::addToLog('New sub category ' . $request->input('name') . ' was added');

        
        return redirect('/categories/'.$id.'/showSub')->with('success','Sub Category Added');
    }

    public function storeSubCategoryItem(Request $request){
        $parent_id = $request->parent_id;
        $data = $request->data;        
        $param = array();
        parse_str($data, $param); //unserialize jquery string data         
        $subcatId = $param['subcatID'];  
        $catName =  $param['name'];  

        $cate = new Category;
        $cate->name = $catName;
        $cate->parent_id = $parent_id;
        $cate->child_id = $subcatId;
        $cate->created_by = auth()->user()->id;
        $cate->save();
        \Log::addToLog('New sub category item ' . $request->input('name') . ' was added');

        
        // // return redirect('/categories/'.$id.'/showSub')->with('success','Sub Category Item Added');
        return response()->json(['success' => true, 'msg'=>'Sub Category Item Added', 'url'=> '/categories/'.$parent_id.'/showSub']);
    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cate = Category::find($id);
        $cate->delete();

        $cate->documents()->detach();

        \Log::addToLog('Sub Category ID '.$id.' was deleted');

        return redirect()->back()->with('success','Sub Categories Deleted!');
    }

    // multiple checkbox delete
    public function deleteMulti(Request $request)
    {   
        $ids = $request->ids;
        //dd($ids);
        //$parent_cat=SubCategory::where('id', '=', $ids)->limit(1)->pluck('parent_cat');
        
        DB::table("category")->whereIn('id', explode(",", $ids))->delete();

        \Log::addToLog('All sub categories '.$ids.' were deleted');

        //return redirect('/categories/'.$parent_cat.'/addSub')->with('success','Sub Categories Deleted!');
        // return redirect()->back()->with('success','Sub Categories '.$ids.' Deleted!');
        return response()->json(['success' => true, 'msg'=>'Sub Categories '.$ids.' Deleted!']);
        
    }

}
