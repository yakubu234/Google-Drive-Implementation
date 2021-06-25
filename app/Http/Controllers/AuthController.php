<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => ['required','string','between:2,100'],
            'last_name' => ['required','string','between:2,100'],
            'email' => 'required|string|email|max:100|unique:users',
            'gender' => ['required','string'],
            'phone' => ['required','string','min:11','max:15'],
            'password' => ['required','string','confirmed','min:6'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $Uuid = $this->generateUid();

        $user = User::create([
            'uid' => $Uuid,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email ,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        $data = $this->login($request);

        return response()->json($data, 201);        
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
        'message' => 'Invalid login details'
                ], 401);
            }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'details' => (new UserResource($user))
        ]);
    }
    
    private function generateUid()
    {
        return md5(time().rand());
    }

    public function getUserDetails( Int $user_id)
    {
        $user = User::where('id', $user_id)->first();
        return response()->json(["User_Details" => ( new UserResource($user))],200);
    }

    public function updateUserDetails(Request $request)
    {
        $userDetails=User::find($request->id);
        $userDetails->update($request->all());

        return response()->json([
            "message" => "user updated successfully",
            "user_details" => $request->all(),
        ],201);
    }

    public function deleteUserDetails( Int $user_id )
    {
        User::destroy($user_id);

        return response()->json(['message' => 'user successfully deleted'], 200);
    }
}
