<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;

class UsersController extends ApiHelpersController
{

    public function index()
    {
        $users = User::select('id','first_name','last_name','email')->get();
        return $this->fire($users);
    }

    public function create(Request $request)
    {
        $this->validate($request,[
            'email'      => 'email|required|unique:users',
            'password'   => 'required|min:10',
            'first_name' => 'required',
            'last_name'  => 'required',
            'photo'      => 'required',
        ]);

        $request->merge(['password'=>bcrypt($request->password)]);
        $request->merge(['photo'=>base64_image($request->photo,'users')]);
        $user = User::create($request->all());
        return $this->fire($user,'User Created Successfully');
    }

    public function update(Request $request,User $user)
    {
        if(!$user){
            return $this->fire([],null,['User Not Found']);
        }
        $this->validate($request,[
            'email'      => 'email|unique:users,email,'.$user->id,
            'password'   => 'min:10',
            'photo'      => 'base64Image',
        ]);

        if($request->has('password')){
            $request->merge(['password'=>bcrypt($request->password)]);
        }else{
            $request->merge(['password'=>$user->password]);
        }
        if($request->has('photo')){
            $request->merge(['photo'=>base64_image($request->photo,'users')]);
        }
        $user->update($request->all());
        return $this->fire($user,'User Updated Successfully');
    }

    public function delete(User $user)
    {
        if(!$user){
            return $this->fire([],null,['User Not Found']);
        }
        $user->delete();
        return $this->fire($user,'User Deleted Successfully');
    }

}
