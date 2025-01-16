<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Http\Requests\StoreActionRequest;
use App\Http\Requests\UpdateActionRequest;
use App\Http\Resources\ActionResource;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actions = Action::all();
        $actions->load(['user', 'customer']);

        return response()->json(['actions' => ActionResource::collection($actions)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActionRequest $request)
    {
        $validatedData = $request->validated();
        try {
            DB::beginTransaction();

            $action = Action::create($validatedData);
            $action->load(['user', 'customer']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return response()->json(['action' => new ActionResource($action)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Action $action)
    {
        $action->load(['user', 'customer']);
        return response()->json(['action' => new ActionResource($action)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActionRequest $request, Action $action)
    {
        $validatedData = $request->validated();
        $action->update($validatedData);
        $action->load(['user', 'customer']);
        return response()->json(['action' => new ActionResource($action)], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Action $action)
    {
        $action->delete();
        return response()->json([], 200);
    }
}
