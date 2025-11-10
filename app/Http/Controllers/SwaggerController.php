<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwaggerController extends Controller
{
    /**
     * Display the Swagger UI documentation
     */
    public function index()
    {
        return view('swagger.index');
    }

    /**
     * Serve the OpenAPI JSON specification
     */
    public function json()
    {
        try {
            // Generate OpenAPI documentation
            $swagger = \OpenApi\Generator::scan([
                app_path('Http/Controllers/API')
            ]);

            return response($swagger->toJson(), 200)
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate API documentation',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
