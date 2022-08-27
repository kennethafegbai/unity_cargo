<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Contact;
use Illuminate\Support\Facades\Validator; 

//use Validator;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $successStatus = 200;

     /**
     * @OA\Get(
     *      path="/api/contact-us",
     *      operationId="retrieve_contact_us",
     *      tags={"contact us"},
     *      security={
     *      {"passport": {}},
     *      },
     *      summary="contact us",
     *      description="Returns all contacts",
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
        $contact = Contact::all();
        if(count($contact)==0){
            return response()->json([
                'success'=>false,
                'message'=>'No record found',
            ], 404);
        }
        return response()->json([
            'success'=>true,
            'message'=>'all contacts',
            'data'=>$contact
        ], $this->successStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 /**
        * @OA\Post(
        * path="/api/contact-us",
        * operationId="submit-contact-us",
        * tags={"contact"},
        *security={ {"passport": {} }},
        * summary="submit contact form",
        * description="submit contact form",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name", "phone_number", "email", "postal_code", "subject", "message"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="phone_number", type="text"),
        *               @OA\Property(property="email", type="text"),     
        *               @OA\Property(property="postal_code", type="text"), 
        *               @OA\Property(property="subject", type="text"),   
        *               @OA\Property(property="message", type="text"),              
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Create Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Create Successful",
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'postal_code' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $contact = Contact::create($input);
        return response()->json([
            'success'=>true, 
            'message'=>'Create successful',
            'data'=>$contact
        ], $this->successStatus);
    }

  
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
/**
        * @OA\Delete(
        * path="/api/contact-us/{id}",
        * operationId="delete-contact-us",
        * tags={"delete contact"},
        *security={ {"passport": {} }},
        * summary="delete contact",
        * description="delete contact",
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
        $contact = Contact::find($id);
        $contact->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Delete successful',
            'data'=>$contact
        ], $this->successStatus);

    }
}
