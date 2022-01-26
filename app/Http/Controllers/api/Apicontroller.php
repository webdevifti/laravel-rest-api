<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Apicontroller extends Controller
{
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
}
