<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
//use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Routing\Controller;
//use App\Models\User;

class VerifyEmailController extends Controller
{
     /**
        * @OA\Post(
        * path="/api/email/verification-notification",
        * operationId="sent email verified link",
        * tags={"sent email verified link"},
        *security={ {"passport": {} }},
        * summary="sent email verified link",
        * description="sent email verified link",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email"},
        *               @OA\Property(property="email", type="text"),     
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Email already verified",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="verification-link-sent successfully",
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

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            // return [
            //     'message' => 'Already Verified'
            // ];
            return response()->json([
                'message'=>'Already Verified',
            ], 201);
        }

        $request->user()->sendEmailVerificationNotification();

        // return ['status' => 'verification-link-sent'];
        return response()->json([
            'message'=>'verification-link-sent',
        ], 200);
    }



     /**
     * @OA\Get(
     *      path="/api/verify-email/{id}/{hash}",
     *      operationId="verify email",
     *      tags={"verify email"},
     *      security={
     *      {"passport": {}},
     *      },
     *      summary="verify email",
     *      description="verify email",
     *      @OA\Response(
     *          response=200,
     *          description="Email verification Successful",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *           @OA\Response(
     *          response=201,
     *          description="Email already verified",
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
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            // return [
            //     'message' => 'Email already verified'
            // ];
            return response()->json([
                'message'=>'Email already verified',
            ], 201);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // return [
        //     'message'=>'Email has been verified'
        // ];
        return response()->json([
            'message'=>'Email has been verified',
        ], 200);
    }
}
