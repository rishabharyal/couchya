<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	public function register(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => ['required'],
			'email' => ['required', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8'],
            'country' => ['required'],
            'country_code' => ['required'],
            'phone_number' => ['required'],
		]);


		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'data' => $validator->errors()
			]);
		}

		$user = new User();
		$user->name = $request->get('name');
		$user->password = bcrypt($request->password);
		$user->email = $request->get('email');
        $user->country = $request->get('country');
        $user->phone_number = $request->get('phone_number');
        $user->country_code = $request->get('country_code');
		$user->save();

        event(new Registered($user));

		$token = $user->createToken('couchya');

    	return response()->json([
    		'success' => true,
    		'token' => $token->plainTextToken,
    		'name' => $user->name,
    		'email' => $user->email
    	]);

	}

    public function login(Request $request) {
    	$email = $request->get('email');
    	$password = $request->get('password');
    	if (!$email || !$password) {
    		return response()->json([
    			'success' => false,
    			'message' => 'You must provide email and password.'
    		]);
    	}

    	$user = Auth::attempt([
    		'email' => $email,
    		'password' => $password
    	]);

    	if (!$user) {
    		return response()->json([
    			'success' => false,
    			'message' => 'The user and password combination is wrong.'
    		]);
    	}

    	$user = Auth::user();

    	$token = $user->createToken('couchya');

    	return response()->json([
    		'success' => true,
    		'token' => $token->plainTextToken,
    		'name' => $user->name,
    		'email' => $user->email
    	]);
    }
}
