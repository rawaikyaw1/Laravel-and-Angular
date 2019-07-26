<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;
use Validator;

class NewsPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news_posts = NewsPost::all();
        return response()->json([
            'news_posts' =>$news_posts
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'news_category_id' => ['required'],
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }
        
        $image = [];
        if($request->hasfile('thumbnail_img')) 
        {
            foreach ($request->file('thumbnail_img') as $key => $value) {
                $file = $value;
                $name = $file->getClientOriginalName();
                // $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename =time().'.'.$name;
                $file->move('uploads/news/thumbnail/', $filename);
                $image[] = $filename;
            }            
        }
        
        $request->merge([
            'created_by'=>$request->login_id, //this login_id is come from middleware
            'thumbnail'=> json_encode($image),
        ]);
        dd($request->all());
        $save = NewsPost::create($request->all());

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
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Http\Response
     */
    public function show(NewsPost $newsPost)
    {
        return response()->json([
            'newsPost' =>$newsPost
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Http\Response
     */
    public function edit(NewsPost $newsPost)
    {
        return response()->json([
            'newsPost' =>$newsPost
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewsPost $newsPost)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'news_category_id' => ['required'],
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }
        
        $image = [];
        if($request->hasfile('thumbnail_img')) 
        {
            foreach ($request->file('thumbnail_img') as $key => $value) {
                $file = $value;
                $name = $file->getClientOriginalName();
                // $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename =time().'.'.$name;
                $file->move('uploads/news/thumbnail/', $filename);
                $image[] = $filename;
            }            
        }
        
        $request->merge([
            'created_by'=>$request->login_id, //this login_id is come from middleware
            'thumbnail'=> json_encode($image),
        ]);


        $update = $newsPost->update($request->all());

        if ($update) {

            return response()->json([
                'message' =>'Successfully update.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsPost $newsPost, Request $request)
    {
        if ($newsPost) {

            $newsPost->update(['deleted_by'=>$request->login_id]);
            $newsPost->delete();
            return response()->json([
                'message' =>'Successfully deleted.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }
}
