<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{

    public function CategoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage', [
            "categories" => $categories,
        ]);
    }
    public function CategorySavePage(Request $request)
    {
        $category_id = $request->query('id');
        $user_id = $request->header('id');
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();
        return Inertia::render('CategorySavePage', [
            "category" => $category,
        ]);
    }

    public function CreateCategory(Request $request)
    {
        $user_id = $request->header('id');

        Category::create([
            "name" => $request->input('name'),
            "user_id" => $user_id
        ]);

        $data = ['message' => "Category Create Successfully ", 'status' => true, 'error' => ''];
        return redirect('/category-page')->with($data);
    }

    public function CategoryList(Request $request)
    {
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return $categories;
    }


    public function CategoryById(Request $request)
    {
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->where('id', $request->id)->first();
        return $categories;
    }

    public function CategoryUpdate(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Category::where('user_id', $user_id)->where('id', $id)->update([
            'name' => $request->input('name'),
        ]);
      
        $data = ['message' => "Category Updated Successfully ", 'status' => true, 'error' => ''];
        return redirect('/category-page')->with($data);
    }


    public function CategoryDelete(Request $request, $id)
    {
        $user_id = $request->header('id');
        Category::where('user_id', $user_id)->where('id', $id)->delete();
       
        $data = ['message' => "Category Deleted Successfully ", 'status' => true, 'error' => ''];
        return redirect('/category-page')->with($data);
    }
}
