<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //

    public function get(){
        $products = Product::where('is_available', '=', 1)->get();
        foreach ($products as $product){
            $product['category'] = $product->category()->first();
        }
        return response()->json(['response'=>$products], 200);
    }

    public function getByCategory($id){
        $category = Category::find($id);
        if ($category != null){
            $products = $category->products()->where('is_available', '=', 1)->get();
            return response()->json(['response'=>$products],400);
        }
        return response()->json(['response'=>'Category not found'],400);
    }

    public function getAll(){
        $products = Product::all();
        foreach ($products as $product){
            $product['category'] = $product->category()->first();
        }
        return response()->json(['response'=>$products], 200);
    }

    public function find($slug){
        $product = Product::where('slug', '=', $slug)->first();
        if($product == null){
            return response()->json(['response'=>'Product not found'], 400);
        }
        return response()->json(['response'=>$product], 200);
    }

    public function findById($id){
        $product = Product::find($id);
        if($product == null){
            return response()->json(['response'=>'Product not found'], 400);
        }
        return response()->json(['response'=>$product], 200);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'details' => 'required|string|max:255',
            'description' => '',
            'quantity' => 'required|integer',
            'sizes' => 'string|max:255',
            'color' => 'string|max:255',
            'image' => 'image|nullable|max:1999'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // File upload
        if($request->hasFile('image')){

            // Get full filename
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename."_".time().".".$extension;
            // Upload image
            $path = $request->file('image')->storeAs('public/products', $fileNameToStore);

            // This is gonna create folder in /storage/app/public/ as /covers and store the uploaded images,
            // We need to create symlink to the public/ folder

        } else {
            $fileNameToStore = 'default_product.png';
        }

        $product = new Product();
        $product->name = $request->input('name');
        $product->slug = $this->slugMaker($request->input('name'));
        $product->details = $request->input('details');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->rating = 0;
        $product->category_id = $request->input('category_id');
        $product->size = $request->input('size');
        $product->color = $request->input('color');
        $product->image = $fileNameToStore;
        $product->save();

        return response()->json(['response'=>'Product created'],200);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'details' => 'required|string|max:255',
            'description' => '',
            'quantity' => 'required|integer',
            'sizes' => 'string|max:255',
            'color' => 'string|max:255',
            'image' => 'image|nullable|max:1999'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // File upload
        if($request->hasFile('image')){

            // Get full filename
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename."_".time().".".$extension;
            // Upload image
            $path = $request->file('image')->storeAs('public/products', $fileNameToStore);

            // This is gonna create folder in /storage/app/public/ as /covers and store the uploaded images,
            // We need to create symlink to the public/ folder

        } else {
            $fileNameToStore = 'default_product.png';
        }

        $product = Product::find($id);
        if ($product == null) return response()->json(['response'=>'Cannot find product'], 400);
        $catId = $product->category()->first()->id;

        $product->name = $request->input('name');
        $product->slug = $this->slugMaker($request->input('name'));
        $product->details = $request->input('details');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->size = $request->input('size');
        $product->color = $request->input('color');
        $product->is_available = $request->input('is_available');
        $product->is_new = $request->input('is_new');
        $product->is_sale = $request->input('is_sale');
        $product->price_sale = $request->input('price_sale');
        $product->category_id = $catId;
        if($request->hasFile('image')){
            Storage::delete('public/covers/'.$product->image);
            $product->image = $fileNameToStore;
        }
        $product->save();
        return response()->json(['response'=>'Product updated'],200);
    }

    public function slugMaker($title){
        $lowerAndNonCharacters = str_replace("[^a-z0-9 ]", "", strtolower($title));
        $onlyOneSpace = str_replace("[ ]{2,}", "-",$lowerAndNonCharacters);
        return str_replace(' ', '-',$onlyOneSpace);
    }
}
