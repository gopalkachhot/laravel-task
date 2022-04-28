<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     public function login(Request $request)
     {
         $user = User::where('email',$request->email)->first();
         if ($user && Hash::check($request->password,$user->password)) {
             
            $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [
                'user'=>$user,
                'token'=>$token
            ];
            return response($response,202);
         }
         else {
             return response('data has not match');
         }


     }

    public function index()
    {
        $user = User::paginate(25);
        return response($user,202);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ruls = [
            'name'=>'required',
            'email'=>'required',
            'password'=>'required'
        ];
        $validator = Validator::make($request->all(),$ruls);
        if ($validator->fails()) {
            return response($validator->errors());
        }
        else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->name); 

            $result = $user->save();
            if ($result) {
                return response($user,202);
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response($user,202);
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
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->name);

        $result = $user->update();
        return response($user,202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $result= $user->delete();
        return response($user,202);
    }
    public function serch($name)
    {
        $user = User::where('name','like','%'.$name.'%')->get();

        return response($user);

    }
}
