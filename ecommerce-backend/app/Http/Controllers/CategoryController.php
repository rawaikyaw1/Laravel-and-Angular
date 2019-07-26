<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'categories' =>$categories
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $request->merge([
            'created_by'=>$request->login_id //this login_id is come from middleware
        ]);

        $save = Category::create($request->all());

        if ($save) {
            return response()->json([
                'message' =>'Successfully created.'
            ], 200);
        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'category' =>$category
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return response()->json([
            'category' =>$category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $request->merge([
            'updated_by'=> $request->login_id
        ]);
        $update = $category->update($request->all());

        if ($update) {
            return response()->json([
                'message' =>'Successfully updated.'
            ], 200);
        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Request $request)
    {
        if ($category) {

            $category->update(['deleted_by'=>$request->login_id]);
            $category->delete();
            return response()->json([
                'message' =>'Successfully deleted.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }
}
