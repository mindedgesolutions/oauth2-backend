<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FactoryController extends Controller
{
    public function index()
    {
        $data = Factory::orderBy('name')->get();

        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
