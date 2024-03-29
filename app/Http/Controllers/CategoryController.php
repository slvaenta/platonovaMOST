<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Facades\DummyJson;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getCategories()
    {
        $categories = DummyJson::getCategories();
        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
        return response()->json();
    }
    public function index()
    {
        $categories = Category::withTrashed()->get(['id', 'name', 'deleted_at']);
        if($categories->count()>0){
            $data = [
                'status' => 200,
                'categories' => $categories
            ];
            return response()->json($data, 200);
        }
        else{
            $data = [
                'status' => 404,
                'categories' => 'No Records Found'
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
        $category = Category::find($id);
        if($category){
            return response()->json([
                'status' => 200,
                'category' => $category
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such Category Found!"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:categories,name|max:191'
        ]);       
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else{
            $category = Category::find($id);
            if($category){
                $category->update([
                    'name' => $request->name
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "Category Updated Succesfully"
                ], 200);
            }
            else{
                return response()->json([
                    'status' => 404,
                    'message' => "No Such Category Found!"
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => "Category Was Deleted Succesfully"
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such Category Found!"
            ], 404);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:categories,name|max:191'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else{
            $category = Category::create([
                'name' => $request->name
            ]);
            if($category){
                return response()->json([
                    'status' => 201,
                    'message' => "Category Created Succesfully"
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
        $category = Category::withTrashed()->find($id);
        if($category){
            $category->restore();
            return response()->json([
                'status' => 200,
                'message' => "Category Was Restored Succesfully"
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such Category Found!"
            ], 404);
        }
    }
}
