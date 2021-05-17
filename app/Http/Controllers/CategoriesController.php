<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DB;

class CategoriesController extends Controller
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
    public function index()
    {
        $categories = Category::where('parent_id', 0)->get();
        $categories_name = DB::table("category")->where("parent_id", 0)->pluck('name', 'id');
        return view('categories.index', compact('categories', 'categories_name'));
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);



        $cate = new Category;
        $cate->name = $request->input('name');
        $cate->parent_id = empty($request->input('parent_id')) ? 0 : $request->input('parent_id');
        if(!empty($request->input('sub_child_id'))) $child_id = $request->input('sub_child_id');
        elseif(!empty($request->input('child_id'))) $child_id = $request->input('child_id');
        else $child_id = 0;
        $cate->child_id = $child_id;
        $cate->created_by = auth()->user()->id;
        $cate->save();
        //$cate = Category::create($request->only('name'));


        \Log::addToLog('New category ' . $request->input('name') . ' was added');

        return redirect('/categories')->with('success', 'Category Added');
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
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
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
            'categoryName' => 'string|required'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->input('categoryName');
        $category->save();

        \Log::addToLog('Category ID ' . $id . ' was edited');

        return redirect('categories')->with('success', 'Category Updated!');
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

        \Log::addToLog('Category ID ' . $id . ' was deleted');

        return redirect('/categories')->with('success', 'Category Deleted');
    }

    // multiple checkbox delete
    public function deleteMulti(Request $request)
    {
        $ids = $request->ids;
        DB::table("category")->whereIn('id', explode(",", $ids))->delete();

        \Log::addToLog('All categories were deleted');

        // return redirect('/categories')->with('success', 'Categories Deleted!');
        return response()->json(['success' => true, 'msg'=>'Categories Deleted!']);
    }

    public function getCat()
    {
        $cat = Category::where('parent_id', 0)->pluck('name', 'id');
        return response()->json($cat);
    }

   public function getSubCat($id)
    {
        $cities = Category::where("parent_id", $id)
            ->where("child_id", 0)
            ->pluck('name', 'id');
        return json_encode($cities);
    }

    public function getChildCat($id)
    {
        $cities = Category::where("child_id", $id)
            ->pluck('name', 'id');
        return json_encode($cities);
    }

    public function getParentCat($id){
        $result = Category::where("id", $id)->pluck('name', 'parent_id','child_id');
        
        return $result;

    }
}
