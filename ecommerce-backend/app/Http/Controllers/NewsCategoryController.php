<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Validator;

class NewsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news_categories = NewsCategory::all();
        return response()->json([
            'news_categories' =>$news_categories
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

        $save = NewsCategory::create($request->all());

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
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\Response
     */
    public function show(NewsCategory $newsCategory)
    {
        return response()->json([
            'newsCategory' =>$newsCategory
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(NewsCategory $newsCategory)
    {
        return response()->json([
            'newsCategory' =>$newsCategory
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewsCategory $newsCategory)
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
        $update = $newsCategory->update($request->all());

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
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsCategory $newsCategory)
    {
        if ($newsCategory) {
            
            $newsCategory->update(['deleted_by'=>$request->login_id]);
            $newsCategory->delete();
            return response()->json([
                'message' =>'Successfully deleted.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }
}
