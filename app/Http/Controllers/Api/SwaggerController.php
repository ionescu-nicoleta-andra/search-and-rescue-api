<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Search & Rescue API",
 *      description="This is the API documentation for the Search & Rescue platform.",
 *      @OA\Contact(
 *          email="support@searchandrescue.test"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Main API Server"
 * )
 *
 * @OA\SecurityScheme(
 *       securityScheme="bearerAuth",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *       description="Enter your JWT token obtained from /api/login"
 *  )
 */
class SwaggerController extends Controller
{
    //
}
