<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class AuthController extends BaseController
{
    public function signup(Request $request)
    {
        // $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        // $countryCodes = implode(',', array_keys($countryData));
        // $mobileCodes = implode(',',array_column($countryData,'dial_code'));
        // $countries = implode(',',array_column($countryData,'country'));
        //dd($countries);
        // dd($request->all());
        $validate  = Validator::make(
            $request->all(),
            [
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required',
                'confirm-password'=>'required|same:password'
            ]
        );
        if($validate->fails()){
            return $this->sendError('Validation Error',$validate->errors());
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password
        ]);
        $success['token'] = $user->createToken('RestApi')->plainTextToken;
        return $this->sendResponse($success,'Registration Successfully');
        // return response()->json([
        //     'status'=>true,
        //     'message'=>'Signup Successfully',
        //     'user'=>$user
        // ],200);
    }
    public function loginView()
    {
        return view('auth/login');
    }
    public function login(Request $request)
    {
        $validate  = Validator::make(
            $request->all(),
            [
                'email'=>'required|email',
                'password'=>'required'
            ]
        );
        if($validate->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Authentication Fails',
                'errors'=>$validate->errors()->all()
            ],401);
        }
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('Api Token')->plainTextToken;
            $success['name'] = $user->name;
            $success['token_type'] = "bearer";
            // return response()->json($success,'Login Successfully');
            return $this->sendResponse($success,'Login Successfully');

            // return response()->json([
            //     'status'=>true,
            //     'message'=>'Login Successfully',
            //     'token'=>Auth::user()->createToken('Api Token')->plainTextToken,
            //     'token_type'=>'bearer'
            // ],200);
        }else{
            return $this->sendError("Unauthorized",['error'=>"Yor are unauthorized"]);
        }
    }
    public function logout(Request $request)
    {
        
        // auth()->user()->tokens()->delete();
        // Auth::user()->tokens()->delete();
        // return $this->sendResponse([],'Logout');
        $user = $request->user();
        $user->tokens()->delete();
        return $this->sendResponse([],'Logout');
        // return response()->json([
        //     'status'=>true,
        //     'user'=>$user,
        //     'message'=>'Logged out Successfully.'
        // ],200);
    }
}
