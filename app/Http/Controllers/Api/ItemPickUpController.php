<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemPickUp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

//use Validator;

class ItemPickUpController extends Controller
{
    public $successStatus = 200;

    /**
     * @OA\Get(
     *      path="/api/item-pickup",
     *      operationId="retrieve-item-pickup",
     *      tags={"All item-pickup"},
     *      security={
     *      {"passport": {}},
     *      },
     *      summary="All item-pickup",
     *      description="Returns all item-pickup",
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
        $limit = request()->get('limit');

        $itempickup = ItemPickUp::all();
        if ($limit != "") {
            $itempickup->take($limit);
        }
        foreach ($itempickup as $item) {
            $item["last_update"] = Carbon::create($item->updated_at)->diffForHumans();
        }
        return response()->json([
            'success' => true,
            'message' => 'item-pickup Lists',
            'data' => $itempickup,
            'token' => auth()->user(),
        ], $this->successStatus);
    }

    public function myparcels()
    {
        $limit = request()->get('limit');
        $itempickup = ItemPickUp::with('History')->where("user_id", auth()->user()->id)->orderBy("id", "desc")->get();
        foreach ($itempickup as $item) {
            $item["last_update"] = Carbon::create($item->updated_at)->diffForHumans();
        }
        return response()->json([
            'success' => true,
            'message' => 'item-pickup Lists',
            'data' => $limit != "" ? $itempickup->take($limit) : $itempickup
        ], $this->successStatus);
    }



    /**
     * @OA\Post(
     * path="/api/item-pickup",
     * operationId="request-item-pickup",
     * tags={"request item-pickup"},
     *security={ {"passport": {} }},
     * summary="request item-pickup",
     * description="request item-pickup",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"first_name","last_name","phone_number", "email","collection_address","postal_code","package_type", "commodity_type",
     *                         "collection_date","collection_time","item_descripttion","estimated_weight","number_of_boxes","receiver_first_name",
     *                         "receiver_last_name","receiver_address","receiver_city","receiver_state","receiver_phone_number","receiver_phone_number2","delivery_type","payment_type", "tracking_id"},
     *               @OA\Property(property="first_name", type="text"),  
     *               @OA\Property(property="last_name", type="test"),      
     *               @OA\Property(property="phone_number", type="text"),      
     *               @OA\Property(property="email", type="text"),  
     *               @OA\Property(property="collection_address", type="text"),   
     *               @OA\Property(property="postal_code", type="text"),  
     *               @OA\Property(property="package_type", type="text"),          
     *               @OA\Property(property="commodity_type", type="text"),          
     *               @OA\Property(property="collection_date", type="text"),          
     *               @OA\Property(property="collection_time", type="text"),          
     *               @OA\Property(property="item_descripttion", type="text"),          
     *               @OA\Property(property="estimated_weight", type="text"),          
     *               @OA\Property(property="number_of_boxes", type="text"),          
     *               @OA\Property(property="receiver_first_name", type="text"),          
     *               @OA\Property(property="receiver_last_name", type="text"),          
     *               @OA\Property(property="receiver_address", type="text"),          
     *               @OA\Property(property="receiver_city", type="text"),          
     *               @OA\Property(property="receiver_state", type="text"),          
     *               @OA\Property(property="receiver_phone_number", type="text"),          
     *               @OA\Property(property="receiver_phone_number2", type="text"),          
     *               @OA\Property(property="delivery_type", type="text"),          
     *               @OA\Property(property="payment_type", type="text"),          
     *               @OA\Property(property="tracking_id", type="integer"),          
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="request create Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="request create Successful",
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
        $token = $request->header("token");
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'collection_address' => 'required',
            'postal_code' => 'required',
            'package_type' => 'required',
            'commodity_type' => 'required',
            'collection_date' => 'required',
            'collection_time' => 'required',
            'item_description' => 'required',
            'estimated_weight' => 'required',
            'number_of_boxes' => 'required',
            'receiver_first_name' => 'required',
            'receiver_last_name' => 'required',
            'receiver_address' => 'required',
            'receiver_city' => 'required',
            'receiver_state' => 'required',
            'receiver_phone_number' => 'required',
            'receiver_phone_number2' => 'required',
            'delivery_type' => 'required',
            'payment_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => "All fields are required"], 401);
        }

        $user = User::where("email", auth()->user()->email)->first();
        $input = $request->all();
        $input["user_id"] = $user->id;

        $tracking_id = rand(1111111111, 9999999999);
        $input['tracking_id'] = $tracking_id;
        $itempickup = ItemPickUp::create($input);
        return response()->json([
            'success' => true,
            'message' => 'request successful',
            'data' => $itempickup
        ], $this->successStatus);
    }


    /**
     * @OA\Get(
     * path="/api/item-pickup/{tracking_id}",
     * operationId="item-pickup-details",
     * tags={"Track package"},
     *security={ {"passport": {} }},
     * summary="",
     * description="Track Package",  
     *       @OA\Parameter(
     *           description="Tracking ID of item-pickup",
     *           in="path",
     *           name="id",
     *           required=true,
     *           @OA\Schema(
     *               type="integer",
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
    public function show($tracking_id)
    {
        $itempickup = ItemPickUp::with('History')->where('tracking_id', $tracking_id)->first();
        if (is_null($itempickup)) {
            return response()->json([
                'success' => false,
                'message' => 'No record found'
            ], 404);
        }

        $itempickup["last_update"] = Carbon::create($itempickup->updated_at)->diffForHumans();
        return response()->json([
            'success' => true,
            'message' => 'item-pickup details',
            'data' => $itempickup
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
     * path="/api/item-pickup/{id}",
     * operationId="delete-item-pickup",
     * tags={"delete item-pickup"},
     *security={ {"passport": {} }},
     * summary="delete item-pickup",
     * description="delete item-pickup",
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
        $itempickup = ItemPickUp::find($id);
        $itempickup->delete();
        return response()->json([
            'success' => true,
            'message' => 'Delete successful',
            'data' => $itempickup
        ], $this->successStatus);
    }
}
