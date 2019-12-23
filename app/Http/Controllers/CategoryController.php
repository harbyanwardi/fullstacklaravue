<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware(function($request, $next){

         if(Gate::allows('manage-categories')) return $next($request);
         abort(403, 'Anda tidak memiliki cukup hak akses');
        });
    }

    public function index(Request $request)
    {
        $filterKeyword = $request->get('name');
        if($filterKeyword){
         $categories = \App\Category::where("name", "LIKE", "%$filterKeyword%")->paginate(10);
        }
        else{
            $categories = \App\Category::paginate(10);
        }

        
        return view('categories.index', ['categories' => $categories]);
    }

    public function trash(){
        $deleted_category = \App\Category::onlyTrashed()->paginate(10);
        return view('categories.trash', ['categories' => $deleted_category]); //softdelete
    }

    public function restore($id){ //restore softdelete
     $category = \App\Category::withTrashed()->findOrFail($id);
     if($category->trashed()){
        $category->restore();
     } else {
     return redirect()->route('categories.index')
        ->with('status', 'Category is not in trash');
     }
     return redirect()->route('categories.index')
        ->with('status', 'Category successfully restored');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->get('name');
         $new_category = new \App\Category;
         $new_category->name = $name;
         if($request->file('image')){
             $image_path = $request->file('image')
                            ->store('category_images', 'public');

             $new_category->image = $image_path;
        }
        $new_category->created_by = \Auth::user()->id;
        $new_category->slug = str_slug($name, '-');
        $new_category->save();
        return redirect()->route('categories.create')->with('status', 'Category successfully created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $category = \App\Category::findOrFail($id);
        return view('categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = \App\category::findOrFail($id);
         return view('categories.edit', ['category' => $category]);
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
        $category = \App\category::findOrFail($id);
        $category->name = $request->get('name');
        $category->slug = $request->get('slug');
        
        if($request->file('image')){
             if($category->image && file_exists(storage_path('app/public/' . $category->image))){
             \Storage::delete('public/'.$category->name);
             }
             $file = $request->file('image')->store('category_images', 'public');
             $category->image = $file;
        }
        $category->updated_by = \Auth::user()->id;
        $category->slug = str_slug($category->name);
        $category->save();
        return redirect()->route('categories.edit', ['id' => $id])->with('status', 'category succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = \App\Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('status', 'Category successfully deleted');
    }

    public function ajaxSearch(Request $request){
     $keyword = $request->get('q');
     $categories = \App\Category::where('name', 'LIKE', "%$keyword%")->get();
     // return response()->json($categories);
      return $categories;
    }
}
