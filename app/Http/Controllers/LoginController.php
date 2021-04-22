<?php
namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivity as log;

class LoginController extends BaseController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function registration(Request $request){

        $data = [
            "role" => $request->role,
            "username" => strtolower($request->username),
            "email" => strtolower($request->email),
            "password" => $request->password,
            "password_confirmation" => $request->password_confirmation,
        ];

        // validation with Validator
        $validator = Validator::make($data, [
            "role" => "required",
            "username" => 'required|unique:users|max:30',
            'email' => 'required|email|unique:users|max:35',
            'password' => 'required|confirmed|string| min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        // check validaton
        if($validator->fails())
        {
            return response()->json([
                'error' => true,
                'message' => $validator->errors(),
            ], 400);
        }

        // delete password confirmation
        unset($data['password_confirmation']);

        // insert data user
        $this->user->role_id = $data['role'];
        $this->user->username = $data["username"];
        $this->user->email = $data["email"];
        $this->user->password = Hash::make($data['password']);
        $this->user->save();

        if(!$this->user){
            return response()->json([
                "error" => true,
                "message" => "Something went wrong."
            ], 500);
        }

        return response()->json([
            "message" => "User has been saved",
            "data" => $data
        ], 201);
    }

    public function login(Request $request)
    {
        $data = [
            "username" => strtolower($request->username),
            "password" => $request->password,
            "role" => $request->role,
        ];

        $validator = Validator::make($data, [
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        // check validation
        if($validator->fails())
        {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 400);
        }

        // fetch user base request username
        $user = $this->user->where('username', $data['username'])->first();

        // check username if not exist
        if(!$user){
            return response()->json([
                'error' => true,
                'message' => "User not found."
            ], 401);
        }

        // check password
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'error' => true,
                'message' => "Password doesn't match."
            ], 401);
        }

        // Generate token
        $token = $user->createToken('users')->accessToken;
        $user->active_token = $token;
        $user->save();
        log::addToLog($data['username'] . " login");
        return response()->json([
            "message" => "Login success.",
            "token" => $token,
            "user" => $user,
        ], 200);
    }

    public function logout()
    {
        $user = $this->user->where('username', auth()->user()->username)->first();
        $user->active_token = null;
        $user->save();
        if(!$user){
            return response()->json([
                'error' => true,
                'message' => "Something went wrong",
            ], 500);
        }
        $client_id = auth()->user()->token()->user_id;
        $revoke = DB::table('oauth_access_tokens')->where('client_id', $client_id)->update(['revoked' => true]);
        if(!$revoke){
            return response()->json([
                'error' => true,
                'message' => "Something went wrong",
            ], 500);
        }
        return response()->json([
            'error' => false,
            'message' => "You was logout.",
        ], 200);
    }
}
