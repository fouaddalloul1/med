<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
            $jsonStr = Crypt::decryptString($token);
            $jsonPayload = json_decode($jsonStr, true);
           // echo "id of user".$jsonPayload["tokenable_id"];

            ///return response()->json(["data:"=>$jsonPayload]);
            //            $token = Crypt::decryptString($token);

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
//            $dataOfuser = $request->except(['lang']);

            $var = $request->input('email');
            if(!$var)
                $var = $request->input('phone');
            $password = $request->input('password');
            $dataOfuser = ['email' => $var, 'password' => $password];
            if (Auth::attempt($dataOfuser)) {
                echo "is authenticated";
//#################################################################################################

                $token = $currentUser->createToken('Token_name')->accessToken;
                $encrypted_token = Crypt::encryptString($token);


//                echo "token : ".$token;
                echo "\n";
                // set request headers
                $request->headers->set('token', 'Bearer ' . $token);

//
//                 authenticate user using token
                $currentUser = auth()->user();
                // get user ID
                $userId = $currentUser->id;
//                echo "user id :" . $userId;

//                echo "header of request : ". $request->header('token');
//                echo "\n";
                $request->merge(['idUser' => $userId]);

                $request->merge(['token' => $encrypted_token]);

//      ###################################################################################
                return $next($request);
            } else {
                echo " still not authenticated";
            }
        }
        //this email is not for this password
        return response()->json(['message'=>'The email or phone and password combination is incorrect']);
    }

}
