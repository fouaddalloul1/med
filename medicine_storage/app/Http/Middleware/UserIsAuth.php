<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserIsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//##########################################################################

        //if they(front) send token in header with to me
        if($request->hasHeader('token')){
            $request = Request::capture();
            $token = $request->header('token');

            try {
                $jsonStr = base64_decode($token);
                $jsonPayload = json_decode($jsonStr, true);
                if (!$jsonPayload)
                    return response()->json(['message' => 'null token']);
                if (!isset($jsonPayload['id'])) {
                    return response()->json(['message' => 'token does not have id']);
                }

            }catch (\Exception $exception){
                return response()->json(['message'=>'found error']);
            }
            return $next($request);
        }

//###########################################################################

        $enter = false;
        //check if enter phone or email
        if($request->input('phone') == null && $request->input('email') !== null ||
            $request->input('phone') !== null && $request->input('email') == null)
        {
           // echo "enter  = true  ";
            $enter = true;
        }

        //check if enter (phone or email) and password
        if($enter == false || $request->input('password') == null){

            return response()->json(['message' => 'please fill email or phone and password']);
        }

        //user enters phone  and password
        if($request->input('email') == null)
        {
            //echo "phone : ".$request->input('phone');

            $currentUser = User::where('phone',$request->input('phone'))->first();
        }
            //user enters email   and password
        else
        {
            //echo "  email : ".$request->input('email');

            $currentUser = User::where('email',$request->input('email'))->first();
        }

        //user enter email or phone ,but I do not find in DB
        if ($currentUser == null || !isset($currentUser) && !$request->hasHeader('Authorization'))
        {
            return response()->json(
                ['message' => 'please register this user with this is email or phone  is not found']);
        }

        //If you want to check if a password is correct, you can use the Hash::check method.
        //I receive a request how check if the email and password belong to the same user

        //$currentUser = User::find($request->email);

        if ($currentUser && Hash::check($request->input('password'), $currentUser->password)) {
            // The email or phone and password belong to the same user.
            $dataOfuser = $request->all();
            if (Auth::attempt($dataOfuser)) {
                echo "is authenticated";
//#################################################################################################

                $token = $currentUser->createToken('Token_name')->accessToken;

                // set request headers
                $request->headers->set('token', 'Bearer ' . $token);

                // authenticate user using token
                $currentUser = auth()->user();
//
                // get user ID
                $userId = $currentUser->id;
                echo "user id :" . $userId;


                $request->merge(['token' => $token]);

                $request->merge(['idUser' => $userId]);
//      ###################################################################################
                return $next($request);
//                return $next($request);
            } else {
                echo " still not authenticated";
            }
        }
        //this email is not for this password
        return response()->json(['message'=>'The email or phone and password combination is incorrect']);
    }

}
