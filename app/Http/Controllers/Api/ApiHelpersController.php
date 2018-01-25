<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App;
class ApiHelpersController extends Controller
{
    protected function fire($data = [],$message = 'data fetched successfully',$errorsToShow = [],$scode = 200)
    {
        if(count($errorsToShow)>0)
        {
            return response()->json(['scode'=>400,'message'=>'Some Errors Happened','errors'=>$errorsToShow,'data'=>[]]);
        }

        if(count($data) == 0)
        {
            return response()->json(['scode'=>400,'message'=>'no records to show','errors'=>$errorsToShow,'data'=>[]]);
    	}
        return response()->json(['scode'=>$scode,'message'=>$message,'errors'=>$errorsToShow ,'data'=>$data]);
    }


    protected function doUserLogin($guard,Request $params)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required',
        ];

        $validator = \Validator::make($params->all(),$rules);
        if($validator->fails()){
            return response()->json(['scode'=>400,'message'=>'Some Errors Happened','errors'=>$validator->errors(),'data'=>[]]);
        }
        
        $cerdentials  = ['email'=>$params->email,'password'=>$params->password];
       
        if (Auth::guard($guard)->attempt($cerdentials)) {

            $new_token = ['api_token'=>str_random(60)];
            Auth::guard('usersSession')->user()->tokens()->create($new_token);
            return response()->json(['scode'=>200,'message'=>'Successfully Logged in','errors'=>[],'data'=>array_merge(Auth::guard('usersSession')->user()->toArray(),$new_token)]);

        }
        return response()->json(['scode'=>401,'message'=>'Some Errors happened','errors'=>['wrong email or password'],'data'=>[]]);

    }

    protected function getUserObject()
    {
        return Auth::guard('users')->user()->user;
    }

}
