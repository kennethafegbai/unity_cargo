<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;

class NewPasswordController extends Controller
{

    /**
        * @OA\Post(
        * path="/api/forgot-password",
        * operationId="forgot-password",
        * tags={"forgot-password"},
        *security={ {"passport": {} }},
        * summary="Reset forgotten password",
        * description="Get a link to reset forgotten password",
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
        *          description="reset password link sent to email",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="reset password link sent to email",
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
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            // return [
            //     'status' => __($status)
            // ];

            return response()->json([
                'message'=> __($status),
            ], 201);
        }

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)],
        // ]);
        return response()->json([
            'message'=> "Email not found!!!",
        ], 404);
    }


      /**
        * @OA\Post(
        * path="/api/reset-password",
        * operationId="reset-password",
        * tags={"reset-password"},
        *security={ {"passport": {} }},
        * summary="Get a new password",
        * description="Get a new password to replace forgotten one",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"token"},
        *               required={"email"},
        *               required={"password"},
        *               required={"password_confirmation"},
        *               @OA\Property(property="token", type="text"),
        *               @OA\Property(property="email", type="text"),  
        *               @OA\Property(property="password", type="text"),   
        *               @OA\Property(property="password_confirmation", type="text"),     
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Password reset successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Password reset successfully",
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
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );


        if ($status == Password::PASSWORD_RESET) {
            // return response([
            //     'message'=> 'Password reset successfully'
            // ]);

            return response()->json([
                'message'=> 'Password reset successfully'
            ], 201);
        }

        // return response([
        //     'message'=> __($status)
        // ], 500);

        return response()->json([
            'message'=> "An error occured, try again",
        ], 500);

    } 

}
