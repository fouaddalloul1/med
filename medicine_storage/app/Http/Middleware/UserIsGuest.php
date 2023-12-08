<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        //if user left a field blank
        if($request->input('email') == null || $request->input('phone') == null
            || $request->input('name') == null || $request->input('password') == null){

            echo  $request->input('email') . " ,". $request->input('phone') .
                " ," .$request->input('password'). " , ". $request->input('name');

            return response()->json(['message'=>'please fill name,email,phone,and password']);
        }

//        echo  $request->input('email') . " ,". $request->input('phone') .
//            " ," .$request->input('password'). " , ". $request->input('name');

        $emailOfuser = User::where('email', $request->input('email'))->first();

        if($emailOfuser == null || !isset($emailOfuser)){
            // not found email in users table(this user is new)


            // that checks if the value of the field
            // is unique in the users table .

            //I encountered a problem when I do not specific the name of table
            // error : The error message you’re seeing indicates
            // that the unique validation rule requires at least one parameter.
            // This parameter specifies the name of the database table column that should be unique.
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone|digits:10',
                'password' => 'required|string|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $dataOfuser = $request->all();
            foreach ($dataOfuser as $d){
                echo $d . "  ,  ";
            }

            $user = new User;
            $user->name = $dataOfuser['name'];
            $user->email = $dataOfuser['email'];
            $user->phone = $dataOfuser['phone'];
            $user->password = $dataOfuser['password'];
            $user->save();

            if(Auth::attempt($dataOfuser)){
                echo "is authenticated";
//#################################################################################################

                $token = $user->createToken('TokenName')->accessToken;

                // set request headers
                $request->headers->set('token', 'Bearer ' . $token);

                // authenticate user using token
                $user = auth()->user();
//
//                // get user ID
                $userId = $user->id;
//                echo "user id :" . $userId;
                // use user ID as needed

                $request->merge(['idUser'=>$userId]);

                $request->merge(['token' => $token]);

//      ###################################################################################
                return $next($request);
            }
            else{
                echo " still not authenticated";
            }
           // User::create($dataOfuser);  this way will give me error
//            $user = User::create([
//                'name' => $request->input('name'),
//                'email' => $request->input('email'),
//                'phone' => $request->input('phone'),
//                'password' => Hash::make($request->input('password'))
//            ]);

//            return \response()->json([$dataOfuser]);
            return $next($request);
        }


        return response()->json(['message'=> 'this email is existed,please login instead of  register']);

    }
}
