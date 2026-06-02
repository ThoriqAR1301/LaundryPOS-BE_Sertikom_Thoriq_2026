<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="LaundryPOS API",
 *     version="1.0.0",
 *     description="Dokumentasi API LaundryPOS Ecosystem"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiInfo
{
	// Dummy class to hold OpenAPI annotations for scanning
}
