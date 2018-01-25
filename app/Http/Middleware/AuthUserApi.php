<?php

namespace App\Http\Middleware;

use App\User;
use App\UserTokens;
use Carbon\Carbon;
use Closure;
use Validator;
use Illuminate\Auth\AuthenticationException;
use Auth;
class AuthUserApi
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = Auth::guard('users');
        //$this->auth = Auth::guard('users');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        /*if ($this->auth->guest())
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                $errors = ['you must be logged in'];
                return response()->json(['scode'=>401,'message'=>'Some Errors Happened','errors'=>$errors,'data'=>[]]);
            }
        }*/
        $validator = Validator::make($request->all(),['api_token'=>'required']);
        if ($validator->fails())
        {
            return response()->json(['scode'=>400,'message'=>'Some Errors Habbened','errors'=>$validator->errors()->toArray(),'data'=>[]]);
        }

        $user = User::whereHas('tokens',function($query) use ($request){
            $query->where('api_token',$request->get('api_token'));
        });

        if($user->count() ==  0){
            return response()->json(['scode'=>400,'message'=>'Some Errors Habbened','errors'=>['User Not Found'],'data'=>[]]);
        }

        $beforeThirty = Carbon::now()->subDays(30);
        $token = UserTokens::where('api_token',$request->api_token)->first();
        if($token && $token->last_activity  != null)
        {
            if($token->last_activity < $beforeThirty){
                return response()->json(['scode'=>401,'message'=>'Some Errors Habbened','errors'=>['Expired Token'],'data'=>[]]);
            }
        }
        $token->update(['last_activity'=>Carbon::now()]);
        return $next($request);
    }
}
