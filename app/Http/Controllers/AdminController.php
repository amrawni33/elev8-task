<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function addEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'password' => ['string', 'min:4', 'confirmed'],
        ]);
        $employee = User::create($validated);
        $employee->assignRole('employee');

        return response()->json(['employee' => new UserResource($employee)], 200);
    }

    public function assignCustomerToEmployee(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);
        $customer->update(['user_id' => $validated['user_id']]);
        $customer->load(['user']);

        return response()->json(['customer' => new UserResource($customer)], 201);
    }

}
