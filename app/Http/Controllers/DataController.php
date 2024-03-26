<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowDataRequest;
use App\Services\DataService;
use Illuminate\Http\Response;

class DataController extends Controller
{
    protected $service;

    public function __construct(DataService $service)
    {
        $this->service = $service;
    }

    public function show(ShowDataRequest $request)
    {
        $validated = $request->validated();
        $data = $this->service->show($validated);
        
        return response()->json($data, Response::HTTP_OK);
    }
}
