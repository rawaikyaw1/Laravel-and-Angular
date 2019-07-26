<?php

namespace App\Http\Controllers;

use App\Models\SocialEnterprise;
use App\Models\Sgd;
use App\Models\Sector;
use App\Models\SeFeature;
use Illuminate\Http\Request;
use Validator;

class SocialEnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socialEnterpirses = SocialEnterprise::all();
        return response()->json([
            'socialEnterpirses' =>$socialEnterpirses
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:social_enterprises'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required','numeric'],
            'address' => ['required'],
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }
        
        $image = [];
        if($request->hasfile('images')) 
        {
            foreach ($request->file('images') as $key => $value) {
                $file = $value;
                $name = $file->getClientOriginalName();
                // $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename =time().'.'.$name;
                $file->move('uploads/social-enterprise/images/', $filename);
                $image[] = $filename;
            }            
        }
        

        // $video = [];
        // if($request->hasfile('videos')) 
        // {
        //     foreach ($request->file('videos') as $key => $value) {
        //         $file = $value;
        //         $name = $file->getClientOriginalName();
        //         // $extension = $file->getClientOriginalExtension(); // getting image extension
        //         $filename =time().'.'.$name;
        //         $file->move('uploads/social-enterprise/videos/', $filename);
        //         $video[] = $filename;
        //     }            
        // }
        
    $request->merge([
            'created_by'=>$request->login_id, //this login_id is come from middleware
            'password'=>bcrypt($request->password),
            'se_images'=> json_encode($image),
            // 'se_videos'=> json_encode($video),
        ]);

    $save = SocialEnterprise::create($request->all());

    if ($save) {

        if ($request->is_feature) {
            $se_arr = ['se_id'=>$save->id, 'created_by'=>$request->login_id];

            SeFeature::create($se_arr);
        }

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
     * @param  \App\SocialEnterprise  $socialEnterprise
     * @return \Illuminate\Http\Response
     */
    public function show(SocialEnterprise $socialEnterprise)
    {
        return response()->json([
            'socialEnterprise' =>$socialEnterprise
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SocialEnterprise  $socialEnterprise
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialEnterprise $socialEnterprise)
    {
        $sgds = Sgd::all();
        $sectors = Sector::all();
        return response()->json([
            'socialEnterprise' =>$socialEnterprise,
            'sgds' =>$sgds,
            'sectors' =>$sectors
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SocialEnterprise  $socialEnterprise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SocialEnterprise $socialEnterprise)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:social_enterprises,id'],
            'phone' => ['required','numeric'],
            'address' => ['required'],
        ]); 


        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $image = [];
        if($request->hasfile('images')) 
        {
            foreach ($request->file('images') as $key => $value) {
                $file = $value;
                $name = $file->getClientOriginalName();
                // $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename =time().'.'.$name;
                $file->move('uploads/social-enterprise/images/', $filename);
                $image[] = $filename;
            }  

            $request->merge([
                'se_images'=> json_encode($image),
            ]);          
        }
        
        $request->merge([
            'created_by'=>$request->login_id, //this login_id is come from middleware
            'password'=>bcrypt($request->password)
        ]);

        $update = $socialEnterprise->update($request->all());

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
     * @param  \App\SocialEnterprise  $socialEnterprise
     * @return \Illuminate\Http\Response
     */
    public function destroy(SocialEnterprise $socialEnterprise, Request $request)
    {
        if ($socialEnterprise) {
            $socialEnterprise->update(['deleted_by'=>$request->login_id]);
            $socialEnterprise->delete();
            return response()->json([
                'message' =>'Successfully deleted.'
            ], 200);
        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }
}
