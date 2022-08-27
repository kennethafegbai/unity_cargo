<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

//use Validator;

class AuthController extends Controller
{
    public $successStatus = 200;
    /** 
     * 
     * @return \Illuminate\Http\Response 
     */

     // all staff

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
    * @OA\Get(
    *      path="/api/users",
    *      operationId="users",
    *      tags={"users"},
    *      security={
    *      {"passport": {}},
    *      },
    *      summary="users",
    *      description="Returns all users",
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

   public function index()
   {
    $users = User::all();

    if(count($users)==0){
        return response()->json([
            'success'=>false,
            'message'=>'No record found',
        ], 404);
    }

    return response()->json([
       'success'=>true,
       'message'=>'users',
       'data'=>$users
    ], $this->successStatus);

   }

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
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $data['token'] =  $user->createToken('MyLaravelApp')->accessToken;
            $data['id'] = $user->id;
            $data['success'] = true;
            $data['message'] = 'login successful';

            return response()->json(['data' => $data], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised (wrong credentials)'], 401);
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
    public function logout()
    {
        //$request->user()->tokens()->delete();
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['message' => 'You are logged out'], $this->successStatus);
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
     *               required={"firstname", "lastname", "email", "password", "phone_number", "role"},
     *               @OA\Property(property="firstname", type="text"),
     *               @OA\Property(property="lastname", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="phone_number", type="text"),
     *               @OA\Property(property="role", type="string"),
     *               @OA\Property(property="password", type="password"),
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users',
            'password' => 'required|confirmed|min:6|max:20',
            //'isStaff'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        //$input['password'] = bcrypt($input['password']);
        $input['password'] = Hash::make($request->password);
       // $input['name'] = $request->firstname . " " . $request->lastname;
        $user = User::create($input);
        // event(new Registered($user));
        // $data['token'] =  $user->createToken('MyLaravelApp')->accessToken;
        $data['status'] =  true;
        $data['message'] =  'create successful';
        return response()->json(['data' => $data], $this->successStatus);
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
            'success' => true,
            'message' => 'User details',
            'data' => $user

        ], $this->successStatus);
    }

/**
    * @OA\Post(
        * path="/api/update-user",
        * operationId="update_user",
        * tags={"update user"},
        *security={ {"passport": {} }},
        * summary="update user",
        * description="update user",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/x-www-form-urlencoded",
        *            @OA\Schema(
        *               type="object",
        *               required={"firstname", "lastname", "phone", "address"},
        *               @OA\Property(property="firstname", type="text"),  
        *               @OA\Property(property="lastname", type="test"),      
        *               @OA\Property(property="phone", type="text"),      
        *               @OA\Property(property="address", type="text"),  
        *                
        *            ),
        *        ),
        *    ),      
        *      @OA\Response(
        *          response=201,
        *          description="update Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="update Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Not processed",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        *)
        */

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'=>'required',
            'lastname'=>'required',
            'phone_number'=>'required',
            'address'=>'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $user = User::where("id", Auth::user()->id)->first();
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->phone_number = $request->input('phone_number');
        $user->address = $request->input('address'); 
        $user->update();
        return response()->json(true, 200);
    }



    /**
    * @OA\Post(
        * path="/api/update-password",
        * operationId="update_password",
        * tags={"update password"},
        *security={ {"passport": {} }},
        * summary="update password",
        * description="update password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/x-www-form-urlencoded",
        *            @OA\Schema(
        *               type="object",
        *               required={"password", "old-password"},
        *               @OA\Property(property="password", type="text"),  
        *               @OA\Property(property="old_password", type="test"),       
        *                
        *            ),
        *        ),
        *    ),      
        *      @OA\Response(
        *          response=201,
        *          description="update Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="update Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Not processed",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        *)
        */

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            "password" => "required|confirmed|min:8",
            "old_password" => "required",
        ]);

        
         $user = User::where("id", Auth::user()->id)->first();

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'success'=>true,
                'message'=>'password updated'
            ], $this->successStatus);
        }

        return response()->json(false, 401);
    }

    /**
     * @OA\Delete(
     * path="/api/user/{id}",
     * operationId="delete-user",
     * tags={"delete user"},
     *security={ {"passport": {} }},
     * summary="delete user",
     * description="delete user",
     *       @OA\Parameter(
     *           description="ID of User",
     *           in="path",
     *           name="id",
     *           required=true,
     *           example="1",
     *           @OA\Schema(
     *               type="integer",
     *               format="int64"
     *            )
     *   ),       
     *      @OA\Response(
     *          response=201,
     *          description="delete Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="delete Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Not processed",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *)
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Delete successful',
            'data' => $user
        ], $this->successStatus);
    }
}


