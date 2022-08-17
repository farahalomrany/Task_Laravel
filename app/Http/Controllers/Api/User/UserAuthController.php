<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Auth;

class UserAuthController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)//login with jwt
    {
        try {
            $rules = [//auth rules
                "email" => "required|exists:users,email",
                "password" => "required"

            ];
             //validation
            $validator = Validator::make($request->all(), $rules);//check roles

            if ($validator->fails()) {//if fail
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login
               //variable
            $credentials = $request->only(['email', 'password']); 

            $token = Auth::guard('user-api')->attempt($credentials);//jwt generate token
            

            if (!$token){
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');
            }
           
            $user = Auth::guard('user-api')->user();
            $user->api_token = $token;
            $token_user= User::where('id','=', $user->id)->first();//fetch user by id to store token
            $token_user->token=$user->api_token;//save token in database user authenticated

            $token_user->save();
             //return token
             return $this->returnData('user', $user);//return user information with token

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
 
    }

    public function logout(Request $request)
    {
         $token = $request -> header('auth-token');//get token from header
        if($token){
            try {
                $user=Auth::user($token);
                $token_user= User::where('id','=', $user->id)->first();//fetch user by id to store token
                $token_user->token=NULL;//put token null in database not authenticated

                $token_user->save();

                JWTAuth::setToken($token)->invalidate(); //logout

            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        }else{
            $this -> returnError('','some thing went wrongs');
        }

    } 

    //store user
    public function store(Request $request)
    {
       $user =new User;
           $user->name = $request->name;
           $user->email = $request->email;//unique
           $user->password = bcrypt("$request->password");
           $user->username = $request->username;
           $user->setting = $request->setting;
           $user->rol_id = $request->rol_id;
    
           $user->save();

            $response['data'] = $user;
            $response['message'] = "store success";
            $response['status_code'] = 200;
            return response()->json($response,200) ;


           
    }
}
