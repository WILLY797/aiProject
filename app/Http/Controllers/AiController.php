<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;

class AiController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function handle(Request $request)
    {
        $validated = $request->validate([
            'input' => 'required|string',
        ]);

        $response = $this->aiService->process($validated['input']);

        return response()->json(['output' => $response]);
    }
}
