<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request, $category_id)
    {
        //dd($request->get('deleted'));
        $products = Category::find($category_id)->products;
        if($request->has('deleted')&&$request->boolean('deleted')==true){
            $products = Category::find($category_id)->products()->withTrashed()->get();
        }
        //$products = Category::with('products')->find($category_id)->products;
        if($products->count()>0){
            $data = [
                'status' => 200,
                'products' => $products
            ];
            return response()->json($data, 200);
        }
        else{
            $data = [
                'status' => 404,
                'products' => 'No Products Found'
            ];
            return response()->json($data, 404);
        }
        //return view('categories.index')->with('categories'->$categories);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if($product){
            return response()->json([
                'status' => 200,
                'product' => $product
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such Product Found!"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|exists:products,name|max:191',
            'description' => 'required|string',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);       
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else{
            $product = Product::find($id);
            if($product){
                $product->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'category_id' => $request->category_id
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "Product Updated Succesfully"
                ], 200);
            }
            else{
                return response()->json([
                    'status' => 404,
                    'message' => "No Such Product Found!"
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => "Product Was Deleted Succesfully"
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such Student Found!"
            ], 404);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:products,name|max:191',
            'description' => 'required|string',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else{
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id
            ]);
            if($product){
                return response()->json([
                    'status' => 201,
                    'message' => "Product Created Succesfully"
                ], 201);
            }
            else{
                return response()->json([
                    'status' => 500,
                    'message' => "Something Went Wrong"
                ], 500);
            }
        }
    }

    public function restore(string $id)
    {
        $product = Product::withTrashed()->find($id);
        if($product){
            $product->restore();
            return response()->json([
                'status' => 200,
                'message' => "Product Was Restored Succesfully"
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such Product Found!"
            ], 404);
        }
    }


}
