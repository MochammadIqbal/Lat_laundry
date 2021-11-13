<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function store(Request $request)
    {$validator = Validator::make($request->all(),[
        'nama_user'=>'required|string',
        'username'=>'required',
        'password'=>'required|min:6',
        'role'=>'required'
    ]);
    if ($validator->fails()){
        return response()->json($validator->errors());
    }
    $user = new User();
    $user->nama_user = $request->nama_user;
    $user->username = $request->username;
    $user->password = Hash::make($request->password);
    $user->role = $request->role;

    $user->save();
    
    return response()->json(['message'=>'berhasil diinput']);
    }

    
    public function login(Request $request)
        {
            $credentials = $request->only('username', 'password');
             try {
                if(! $token = JWTAuth::attempt($credentials))
         {
                    return response()->json(['error' => 'invalid_credentials'], 400);
        }
            } catch (JWTException $e) {

                    return response()->json(['error' => 'could_not_create_token', 500]);
        }
 
            $data = [
            'token' => $token,
            'user' => JWTAuth::user()
        ];

            return response()->json([
             'message' => 'Berhasil login',
             'data' => $data
        ]);

 }


    public function getAuthenticatedUser()
 {
    try
 {
    if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['user_not_found'], 404);
    }
     }
    catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
    {
        return response()->json(['token_expired'], $e->getStatusCode());
    }
    catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
     {
        return response()->json(['token_invalid'], $e->getStatusCode());
     }
    catch (Tymon\JWTAuth\Exceptions\JWTException $e)
     {
        return response()->json(['token_absent'], $e->getStatusCode());
     }
        return response()->json(compact('user'));
    }

 public function logout(Request $request)
 {
     if(JWTAuth::invalidate(JWTAuth::getToken())){
         return response()->json(['message'=>'anda sudah logout']);
     } else {
         return response()->json(['message'=>'gagal logout']);

     }
 }
}
