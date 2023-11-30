<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserIsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $emailOfuser = User::where('email', $request->email)->first();
        if($emailOfuser == null || !isset($emailOfuser)){

            // that checks if the value of the field
            // is unique in the users table .
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|max:10',
                'password' => 'required|string|min:8',
            ]);
            User::create($validatedData);
//            $user = User::create([
//                'name' => $request->name,
//                'email' => $request->email,
//                'phone' => $request->phone,
//                'password' => Hash::make($request->password)
//            ]);
            return response()->json(['message'=>'ok,you can login now to home page']);
        }

        return response()->json(['message'=> 'this email is existed']);

    }
}
