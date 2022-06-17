<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator; 
//use Validator;

class AuthController extends Controller
{
    public $successStatus = 200;
	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    // api doc for login

    /**
        * @OA\Post(
        * path="/api/login",
        * operationId="authLogin",
        * tags={"Login"},
        * summary="User Login",
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Login fails",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $data['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
            $data['id'] = $user->id;
            $data['success'] = true;
            $data['message'] = 'login successful';

            return response()->json(['data' => $data], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised (wrong credentials)'], 401); 
        } 
    }


     /**
        * @OA\Post(
        * path="/api/logout",
        * operationId="logout",
        * tags={"Logout"},
        *security={ {"passport": {} }},
        * summary="User Logout",
        * description="Logout User Here",
        *      @OA\Response(
        *          response=201,
        *          description="Logout Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Logout Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Logout fails",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function logout(){
        //$request->user()->tokens()->delete();
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['message'=>'You are logged out'], $this->successStatus );
    }

	/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

// api doc
 /**
        * @OA\Post(
        * path="/api/register",
        * operationId="Register",
        * tags={"Register"},
        * summary="User Register",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email", "password", "c_password"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="email", type="text"),
        *               @OA\Property(property="phone_number", type="text"),
        *               @OA\Property(property="password", type="password"),
        *               @OA\Property(property="c_password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Not processed",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|digits:11|unique:users',
            'password' => 'required|min:6|max:20',
            'c_password' => 'required|min:6|max:20|same:password',
        ]);
		if ($validator->fails()) { 
		    return response()->json(['error'=>$validator->errors()], 401);            
		}
		$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        event(new Registered($user));
        $data['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
        $data['status'] =  true;
        $data['message'] =  'create successful';
		return response()->json(['data'=>$data], $this-> successStatus); 
    }
	
	/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 


    // Api doc for user details

   /**
     * @OA\Post(
     *      path="/api/user-details",
     *      operationId="user-details",
     *      tags={"User details"},
     *      security={
     *      {"passport": {}},
     *      },
     *      summary="User details",
     *      description="Returns of user details",
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    public function userDetails() 
    { 
        $user = Auth::user(); 
        return response()->json([
            'success'=>true,
            'message'=>'User details',
            'data'=>$user
        
        ], $this-> successStatus); 
    }
}
