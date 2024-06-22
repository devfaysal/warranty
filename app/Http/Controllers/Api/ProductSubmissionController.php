<?php

namespace App\Http\Controllers\Api;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'regex:/^[a-zA-Z ]*$/'],
            'serial' => ['required', 'numeric', Rule::exists('units', 'serial')->whereNull('registered_at')],
            'mobile_number' => ['required', 'numeric'],
            'mobile_operator' => ['required', Rule::enum(MobileOperator::class)],
            'connection_type' => ['required', Rule::enum(ConnectionType::class)],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response([
                'response' => $validator->messages(),
                'data' => $request->all(),
            ], 400);
        }
        $data = $validator->getData();
        $unit = Unit::query()
            ->where('serial', $data['serial'])
            ->whereNull('registered_at')
            ->first();
        //Update or Create Customer
        $customer = Customer::updateOrCreate(
            ['mobile_number' => $data['mobile_number']],
            [
                'name' => $data['name'],
                'mobile_operator' => $data['mobile_operator'],
                'connection_type' => $data['connection_type'],
            ]
        );
        $unit->update([
            'customer_id' => $customer->id,
            'registered_at' => now(),
        ]);
        
        return response([
            'response' => 'Product Registered Successfully',
        ], 200);
    }
}
