<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'user_type'     => 'required',
            'mobile_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $useremail  = DB::table('users')->where('email', '=' , $request->email)->where('is_deleted', '=' , 0)->first();

        if(!empty($useremail))
        {
            return response()->json(['status' => 0,'errors' => ['email' => ['The email has already been taken.']]]);
        }

        $user = User::create([
                    'first_name'    => $request->first_name,
                    'last_name'     => $request->last_name,
                    'email'         => $request->email,
                    'user_type'     => $request->user_type,
                    'mobile'        => $request->mobile_number,    
                    'user_timezone' => $request->user_timezone,
                    'latitude'      =>$request->latitude,
                    'longitude'     =>$request->longitude,
                    'password'      => Hash::make($request->password),
                ]);

        if($user)
        {
            return response()->json(['status' => 1,
                'user' => [
                    'id'         => $user->id,
                    'email'      => $user->email,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'user_type'  => $user->user_type,
                ]
            ]);
        }
        else{
            return response()->json(['status' => 0,'errors' => ['error' => ['some server issue']]]);
        }
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
            'user_type' => 'required|integer', // e.g., 1 = buyer, 2 = seller, etc.
        ]);

        $credentials = $request->only('email', 'password', 'user_type');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'status' => 1,
                'message' => 'Login successful',
                'user' => [
                    'id'            => $user->id,
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                    'mobile'        => $user->mobile,
                    'profile_image' => $user->profile_image,
                    'latitude'      => $user->latitude,
                    'longitude'     => $user->longitude,
                    'country_id'    => $user->country_id,
                    'state_id'      => $user->state_id,
                    'city_id'       => $user->city_id,
                    'address1'      => $user->address1,
                    'address2'      => $user->address2,
                    'zip_code'      => $user->zip_code,
                    'user_timezone' => $user->user_timezone,
                    'user_type'     => $user->user_type,
                ]
            ]);
        }

        return response()->json([
            'status' => 0,
            'error'  => 'Invalid credentials or unauthorized user type.'
        ], 401);
    }

    public function getCountries()
    { 
        //This function is for getting the country list
        $countries = DB::table('master_country')
                    ->select('country_id', 'country_name')
                    ->orderBy('country_name')
                    ->get();

        return response()->json([
            'status' => 1,
            'message'  => 'success','countries'=>$countries
        ], 200);
    }
    public function getStates(Request $request)
    {
        //This function is for getting the state list of a country
        $states = DB::table('master_state')
                    ->where('state_country_id',$request->country_id)    
                    ->select('state_id', 'state_name')
                    ->orderBy('state_name')
                    ->get();
        if($states)
        {
            return response()->json([
                'status' => 1,
                'message'  => 'success','states'=>$states
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 0,
                'message'  => 'error'
            ], 401);
        }
    }
    public function getCity(Request $request)
    {
        //This function is for getting the city list of a state
        $city = DB::table('master_city')
                    ->where('city_state_id',$request->state_id)    
                    ->select('city_id', 'city_name')
                    ->orderBy('city_name')
                    ->get();
        if($city)
        {
            return response()->json([
                'status' => 1,
                'message'  => 'success','city'=>$city
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 0,
                'message'  => 'error'
            ], 401);
        }
    }
}