<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
		$user->save();

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
