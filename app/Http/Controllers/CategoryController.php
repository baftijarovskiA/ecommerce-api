<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getAll(){
        $categories = Category::where('available', '=', 1)->get();
        return response()->json(['response'=>$categories],200);
    }

    public function find($id){
        $category = Category::find($id);
        if ($category == null){
            return response()->json(['response'=>'Category not found'],400);
        }
        return response()->json(['response'=>$category],200);
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = $this->slugMaker($request->input('name'));
        $category->save();

        return response()->json(['response'=>'Category created'], 200);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $category = Category::find($id);
        $category->name = $request->input('name');
        $category->slug = $this->slugMaker($request->input('name'));
        $category->save();

        return response()->json(['response'=>'Category updated'], 200);
    }

    public function hide($id){
        $category = Category::find($id);
        if ($category == null){
            return response()->json(['response'=>'Category not found.'], 400);
        }
        $category->available = 0;
        $category->save();

        return response()->json(['response'=>'Category deleted'],200);
    }

    public function slugMaker($title){
        $lowerAndNonCharacters = str_replace("[^a-z0-9 ]", "", strtolower($title));
        $onlyOneSpace = str_replace("[ ]{2,}", "-",$lowerAndNonCharacters);
        return str_replace(' ', '-',$onlyOneSpace);
    }
}
