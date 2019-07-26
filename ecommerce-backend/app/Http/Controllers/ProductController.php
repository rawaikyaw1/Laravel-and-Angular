<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Feature;
use App\Models\Hot;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'products' =>$products
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
            'category_id' => ['required'],
            'se_id' => ['required'],
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
                $file->move('uploads/products/images/', $filename);
                $image[] = $filename;
            }            
        }
        
        $request->merge([
            'created_by'=>$request->login_id, //this login_id is come from middleware
            'product_images'=> json_encode($image),
        ]);

        $save = Product::create($request->all());

        if ($save) {

            $product_arr = ['product_id' => $save->id];

            if ($request->is_feature) {
                Feature::create($product_arr);
            }
            if ($request->is_hot) {
                Hot::create($product_arr);
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' =>$product
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return response()->json([
            'product' =>$product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required'],
            'se_id' => ['required'],
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
                $file->move('uploads/products/images/', $filename);
                $image[] = $filename;
            }            
        }
        
        $request->merge([
            'created_by'=>$request->login_id, //this login_id is come from middleware
            'product_images'=> json_encode($image),
        ]);

        $update = $product->update($request->all());

        if ($update) {
            
            if (!$request->is_feature) {
                $feature = Feature::where('product_id', $product->id)->delete();
            }
            if (!$request->is_hot) {
                $hot = Hot::where('product_id', $product->id)->delete();
            }

            return response()->json([
                'message' =>'Successfully Updated.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Request $request)
    {
        if ($product) {

            $product->update(['deleted_by'=>$request->login_id]);
            $product->hot->delete();
            $product->feature->delete();
            $product->delete();
            return response()->json([
                'message' =>'Successfully deleted.'
            ], 200);

        }

        return response()->json([
            'message' =>'Something was wrong.'
        ], 500);
    }
}
