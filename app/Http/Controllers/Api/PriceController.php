<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Price;
use Illuminate\Support\Facades\Validator; 

//use Validator;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $successStatus = 200;

    /**
     * @OA\Get(
     *      path="/api/price-lists",
     *      operationId="all_prices",
     *      tags={"All price"},
     *      security={
     *      {"passport": {}},
     *      },
     *      summary="All price",
     *      description="Returns all price",
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
        $price = Price::all();
        return response()->json([
            'success'=>true, 
            'message'=>'price lists', 
            'data'=>$price
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
        * path="/api/price-lists",
        * operationId="create_price",
        * tags={"create price"},
        *security={ {"passport": {} }},
        * summary="create price",
        * description="create price",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"state", "door_rate", "collection_rate"},
        *               @OA\Property(property="state", type="text"),  
        *               @OA\Property(property="door_rate", type="text"),      
        *               @OA\Property(property="collecion_rate", type="text"),      
        *               @OA\Property(property="minimum_weight", type="text"),     
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
            'state'=>'required',
            'door_rate'=>'required',
            'collection_rate'=>'required',
            'minimum_weight'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
       $price = price::create($input);
        return response()->json([
            'success'=>true,
            'message'=>'Create successful',
            'data'=>$price,
        ], $this->successStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

      /**
        * @OA\Get(
        * path="/api/price-lists/{state}",
        * operationId="price_details",
        * tags={"price_details"},
        *security={ {"passport": {} }},
        * summary="price details",
        * description="price details",  
        *       @OA\Parameter(
        *           description="state price details",
        *           in="path",
        *           name="state",
        *           required=true,
        *           @OA\Schema(
        *               type="text",
        *            )
        *   ),       
        *      @OA\Response(
        *          response=201,
        *          description="Successful",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successful",
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
    public function show($state)
    {
        $price = Price::where('state', $state)->first();
        if(is_null($price)){
            return response()->json([
                'success'=>false,
                'message'=>'No record found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message'=>'price details',
            'data'=>$price
        ], $this->successStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     
 /**
        * @OA\Put(
        * path="/api/price-lists/{id}",
        * operationId="update_price",
        * tags={"update price"},
        *security={ {"passport": {} }},
        * summary="update price",
        * description="update price",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/x-www-form-urlencoded",
        *            @OA\Schema(
        *               type="object",
        *               required={"state", "door_rate", "collecion_rate", "minimum_weight"},
        *               @OA\Property(property="state", type="text"),  
        *               @OA\Property(property="door_rate", type="test"),      
        *               @OA\Property(property="collection_ratte", type="text"),      
        *               @OA\Property(property="minimum_weight", type="text"),  
        *                
        *            ),
        *        ),
        *    ),
        *       @OA\Parameter(
        *           description="ID of price",
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'state'=>'required',
            'door_rate'=>'required',
            'collection_rate'=>'required',
            'minimum_weight'=>'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
           $price = Price::find($id);
           $price->state = $request->input('state');
           $price->door_rate = $request->input('door_rate');
           $price->collection_rate = $request->input('collection_rate');
           $price->minimum_weight = $request->input('minimum_weight');
           $price->update();
            return response()->json([
                'success' => true,
                'message'=>'Update successful',
                'data'=>$price
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
        * path="/api/price-lists/{id}",
        * operationId="delete_price",
        * tags={"delete price"},
        *security={ {"passport": {} }},
        * summary="delete price",
        * description="delete price",
        *       @OA\Parameter(
        *           description="ID of price",
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
        $price = Price::find($id);
        $price->delete();
        return response()->json([
            'success' => true,
            'message'=>'Delete successful',
            'data'=> $price
        ], $this->successStatus);
    }
}
