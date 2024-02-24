<?php

namespace App\Http\Controllers;

use Laravel\Sanctum\PersonalAccessToken;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @OA\Tag(
     *     name="Auth",
     *     description="API Endpoints for Authentication"
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *      path="/api/rest/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Login",
     *      description="Login to the application",
     *    @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            required={"email", "password"},
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=401,
     *        description="Invalid Credentials"
     *    ),
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->plainTextToken;
            return response(['token' => $token], 200);
        }
        return response(['error' => 'Invalid Credentials'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *      path="/api/rest/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Logout",
     *      description="Logout from the application",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *     @OA\Response(
     *        response=401,
     *       description="Unauthenticated"
     *      ),
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *      path="/api/rest/user",
     *      operationId="user",
     *      tags={"Auth"},
     *      security={{"bearerAuth": {}}},
     *      summary="User",
     *      description="Get the authenticated user",
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     * )
     */

    public function user(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if ($token) {
            return response(['user' => $token->tokenable], 200);
        }
        return response(['error' => 'Unauthenticated'], 401);
    }
}
