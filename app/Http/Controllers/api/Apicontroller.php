<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class Apicontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $users = User::all();
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Display a listing of the users data.',
            'data' => $users,
        ]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => 401,
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Saved Successfully',
                'data' => $user
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "Something Happened Wrong"
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        try{

            User::findOrFail($id);
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Display a specific of the users data.',
                'data' => User::find($id),
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Can Not Find This User'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => 401,
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try{
            
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User Updated Successfully',
                'data' => $user
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "Something Happened Wrong"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            User::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted',
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'This user already deleted before',
            ]);
        }
     
       
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
    public function guard()
    {
        return Auth::guard();
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
