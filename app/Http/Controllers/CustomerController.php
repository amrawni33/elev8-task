<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        $customers->load(['user']);

        return response()->json(['customers' => UserResource::collection($customers)], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $validatedData = $request->validated();
        try {
            DB::beginTransaction();

            $customer = Customer::create($validatedData);
            $customer->load(['user']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return response()->json(['customer' => new UserResource($customer)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load(['user']);
        return response()->json(['customer' => new UserResource($customer)], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validatedData = $request->validated();
        $customer->update($validatedData);
        $customer->load(['user']);

        return response()->json(['customer' => new UserResource($customer)], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([], 201);
    }
}
