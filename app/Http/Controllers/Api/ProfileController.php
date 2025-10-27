<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get logged-in user's profile",
     *     description="Returns the authenticated user's profile details using the JWT token.",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully fetched profile",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nicoleta Andra"),
     *             @OA\Property(property="email", type="string", example="nico@example.com"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="string", example="admin"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => $e->getMessage()], 401);
        }
    }
}
