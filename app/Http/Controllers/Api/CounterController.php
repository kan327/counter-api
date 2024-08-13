<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CounterResource;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CounterController extends Controller
{
    const TOKEN = [
        "3f71b59ca0aa3723a078d0edfa3d417916fef7e2d625dd5e0af0f12375dc1793",
        "62c1b3dfed60e624d261dd0a4ac7b0b5cd4de5e8dd3ad7e2c88d3a539082302e",
        "3fbb363ff52955cdeae327978b6c3d2b02c72797748148999a64fc37b520a748",
    ];

    private function verivyToken($token)
    {
        return in_array($token, self::TOKEN);
    }
    
    public function index($token)
    {
        if(!$this->verivyToken($token)){
            return new CounterResource(false, 'error on token: Token not recognized', []);
        };
        $counter = Counter::where('token', $token)->get();
        return new CounterResource(true, 'successfully retrieved new data', $counter);
    }

    public function store(Request $request, $token)
    {
        if(!$this->verivyToken($token)){
            return new CounterResource(false, 'error on token: Token not recognized', []);
        };
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:255',
            'number' => 'nullable|numeric',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $counter = Counter::create([
            'token' => $token,
            'title' => $request->title,
            'number' => $request->number ?? 1
        ]);

        return new CounterResource(true, 'successfully added new data', $counter);
    }

    public function show($token, $id)
    {
        if(!$this->verivyToken($token)){
            return new CounterResource(false, 'error on token: Token not recognized', []);
        };
        //find counter by ID
        $counter = Counter::where('id', $id)
        ->where('token', $token)
        ->first();

        if(!$counter){
            return new CounterResource(false, 'there is no matching data, try checking the id and token', []);
        }

        //return single Counter as a resource
        return new CounterResource(true, 'successfully get detail data Counter!', $counter);
    }

    public function update(Request $request, $token, $id)
    {
        if(!$this->verivyToken($token)){
            return new CounterResource(false, 'error on token: Token not recognized', []);
        };
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|min:5|max:255',
            'number' => 'nullable|numeric',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $counter = Counter::where('id', $id)
        ->where('token', $token)
        ->first();

        if(!$counter){
            return new CounterResource(false, 'there is no matching data, try checking the id and token', []);
        }
        
        $counter->update(array_filter([
            'title' => $request->input('title'),
            'number' => $request->input('number'),
        ]));

        return new CounterResource(true, 'successfully updated new data', $counter);
    }
    
    public function destroy($token, $id)
    {
        if(!$this->verivyToken($token)){
            return new CounterResource(false, 'error on token: Token not recognized', []);
        };
        $counter = Counter::where('id', $id)
        ->where('token', $token)
        ->first();

        if(!$counter){
            return new CounterResource(false, 'there is no matching data, try checking the id and token', []);
        }
        $counter->delete();
        return new CounterResource(true, 'successfully deleted data', $counter);
    }
}
