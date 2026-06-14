<?php

/**
 * @OA\Info(
 *     title="Absensi Guru API",
 *     version="1.0.0",
 *     description="API untuk sistem absensi guru dengan fitur GPS tracking, foto wajib, dan manajemen penggajian"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Development server"
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="sanctum",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header",
 *         description="Laravel Sanctum token. Format: Bearer {token}"
 *     )
 * )
 */

namespace App\Swagger;
