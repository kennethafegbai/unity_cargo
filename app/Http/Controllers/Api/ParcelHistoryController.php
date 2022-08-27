<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParcelHistory;
use Illuminate\Support\Facades\Validator; 

class ParcelHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $successStatus = 200;
     /**
     * @OA\Get(
     *      path="/api/parcel-history",
     *      operationId="retrieve_parcel-history",
     *      tags={"parcel-history"},
     *      security={
     *      {"passport": {}},
     *      },
     *      summary="parcel-history",
     *      description="Returns all parcel-history",
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
     $parcelhistory = ParcelHistory::all();
     if(count($parcelhistory)==0){
        return response()->json([
            'success'=>false,
            'message'=>'No record found',
        ], 404);
    }
     return response()->json([
        'succes'=>true,
        'message'=>'Parcel histories',
        'data'=>$parcelhistory
     ], $this->successStatus);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
        * @OA\Post(
        * path="/api/parcel-history",
        * operationId="add parcel history",
        * tags={"parcel"},
        *security={ {"passport": {} }},
        * summary="submit contact form",
        * description="add parcel history",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"parcel_id", "tracking_id", "report"},
        *               @OA\Property(property="parcel_id", type="int"),
        *               @OA\Property(property="tracking_id", type="text"),
        *               @OA\Property(property="report", type="text"),                  
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
                'parcel_id' => 'required',
                'tracking_id' => 'required',
                'report' => 'required', 
            ]);
            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()], 401);
            }
    
            $input = $request->all();
            $parcelhistory = ParcelHistory::create($input);
            return response()->json([
                'success'=>true, 
                'message'=>'Create successful',
                'data'=>$parcelhistory
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
        * path="/api/parcel-history/{tracking_id}",
        * operationId="parcel-history-details",
        * tags={"parcel_details"},
        *security={ {"passport": {} }},
        * summary="parcel-history-details",
        * description="parcel-history-details",  
        *       @OA\Parameter(
        *           description="parcel-history-details",
        *           in="path",
        *           name="tracking_id",
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
        $parcelhistory = ParcelHistory::where('tracking_id', $tracking_id)->get();
        if(count($parcelhistory)==0){
            return response()->json([
                'success'=>false,
                'message'=>'No record found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message'=>'parcel history',
            'data'=>$parcelhistory
        ], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        * path="/api/parcel-history/{id}",
        * operationId="update-parcel-history",
        * tags={"update parcel-history"},
        *security={ {"passport": {} }},
        * summary="update parcel-history",
        * description="update parcel-history",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/x-www-form-urlencoded",
        *            @OA\Schema(
        *               type="object",
        *               required={"parcel_id", "tracking_id", "report"},
        *               @OA\Property(property="parcel_id", type="int"),
        *               @OA\Property(property="tracking_id", type="text"),
        *               @OA\Property(property="report", type="text"),                 
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
           // 'parcel_id' => 'required',
           // 'tracking_id' => 'required',
            'report' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
           $parcelhistory = ParcelHistory::find($id);
           //$parcelhistory->parcel_id = $request->input('parcel_id');
          // $parcelhistory->tracking_id = $request->input('tracking_id');
           $parcelhistory->report = $request->input('report');
           $parcelhistory->update();
            return response()->json([
                'success' => true,
                'message'=>'Update successful',
                'data'=>$parcelhistory
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
        * path="/api/parcel-history/{id}",
        * operationId="delete-parcel-history",
        * tags={"delete parcel-history"},
        *security={ {"passport": {} }},
        * summary="delete parcel-history",
        * description="delete parcel-history",
        *       @OA\Parameter(
        *           description="ID of parcel-history",
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
        $parcelhistory = ParcelHistory::find($id);
        $parcelhistory->delete();
        return response()->json([
            'success' => true,
            'message'=>'Delete successful',
            'data'=> $parcelhistory
        ], $this->successStatus);
    }
}
