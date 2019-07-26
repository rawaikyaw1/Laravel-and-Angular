<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Admin;
use Auth;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Hash;

class AdminController extends Controller
{
    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);	

        if ($validator->fails()) {
        	return response()->json($validator->errors(), 500);
        }

        $request->merge(['password'=>bcrypt($request->password)]);
        $admin = Admin::create($request->all());

        if ($admin) {
        	return response()->json([
        		'message' =>'Successfully created'
        	], 200);
        }

    }

    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
        ]);	

        if ($validator->fails()) {
        	return response()->json($validator->errors(), 500);
        }

        $user = Admin::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me){
                $token->expires_at = Carbon::now()->addWeeks(1);
            }            
            $token->client_id = $user->id;
            $token->name = 'admin';
            $token->token = $tokenResult->accessToken;
            $token->save();

        	return response()->json([
        		'user'=>$user,
                'token'=>$tokenResult->accessToken,
                'type'=>'admin',
        		'message'=>'Successfully loggeed in.'
        	], 200);
        }

        return response()->json(['message'=>'Credential does not match.'], 500);
    }

    public function logout(Request $request)
    {

        $header = $request->header('Authorization');
        $auth = Passport::token()->where('token',$header)
                                  ->where('revoked', false)
                                  ->where('expires_at','>=', Carbon::now())
                                  ->first();
        $auth->update(['revoked'=> true]);
        return response()->json([
            'message'=>'Successfully loggeed out.'
        ], 200);
    }
}
