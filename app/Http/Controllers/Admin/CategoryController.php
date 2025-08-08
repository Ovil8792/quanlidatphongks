<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
 /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchId = $request->query('search_id');
        $searchName = $request->query('search_name');

        $query = Category::query();
        if (!empty($searchId)) {
            $query->where('id', (int) $searchId);
        }
        if (!empty($searchName)) {
            $query->where('name', 'like', '%'.$searchName.'%');
        }

        $cat = $query->get();
        return view("admin.category.category", compact("cat", "searchId", "searchName"));
    }

    /**
     * Hiện form tạo danh mục
     */
    public function create()
    {
        return view("admin.category.add");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->input("name");
        $image = $request->input("image",null);
        Category::create(["name"=>$name,"image"=>$image]);
        return redirect(route("admin.category"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cate = Category::findOrFail($id);
        return view("admin.category.edit",compact("cate"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $name = $request->input("name");
        $image = $request->input("image",null);
        Category::where("id",$id)->update(["name"=>$name,"image"=>$image]);
        return redirect(route("admin.category"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect(route("admin.category"));
    }
}
