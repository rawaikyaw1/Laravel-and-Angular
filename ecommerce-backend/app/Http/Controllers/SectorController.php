<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Validator;
use Session;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sectors = Sector::all();
        return response()->json([
            'sectors' =>$sectors
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

        $save = Sector::create($request->all());

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
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function show(Sector $sector)
    {
        return response()->json([
            'data' =>$sector
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function edit(Sector $sector)
    {
        return response()->json([
            'data' =>$sector
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sector $sector)
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
        $update = $sector->update($request->all());

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
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sector $sector, Request $request)
    {

        if ($sector) {

            $sector->update(['deleted_by'=>$request->login_id]);
            $sector->delete();
            return response()->json([
                'message' =>'Successfully deleted.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }
}
