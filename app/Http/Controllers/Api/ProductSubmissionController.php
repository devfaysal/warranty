<?php

namespace App\Http\Controllers\Api;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Unit;
use App\Services\RechargeService;
use Devfaysal\Muthofun\Facades\Muthofun;
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
            'mobile_number' => ['required', 'numeric', 'phone:BD'],
            'mobile_operator' => ['required', Rule::enum(MobileOperator::class)],
            'connection_type' => ['required', Rule::enum(ConnectionType::class)],
        ];
        $messages = [
            'name.required' => 'আপনার নাম লিখুন',
            'serial.required' => 'সিরিয়াল নাম্বার লিখুন',
            'mobile_number.required' => 'মোবাইল নাম্বার লিখুন',
            'mobile_operator.required' => 'অপারেটর নির্বাচন করুন',
            'connection_type.required' => 'সিমের ধরণ নির্বাচন করুন',
            'name.regex' => 'আপনার নাম ইংরেজী অক্ষরে লিখুন',
            'serial.numeric' => 'সিরিয়াল নাম্বার অবশ্যই নাম্বার হতে হবে',
            'mobile_number.numeric' => 'মোবাইল নাম্বার অবশ্যই নাম্বার হতে হবে',
            'mobile_number.phone' => 'মোবাইল নাম্বার অবশ্যই বাংলাদেশী হতে হবে',
            'exists' => 'সিরিয়াল নাম্বার ডাটাবেসে পাওয়া যায়নি',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response([
                'response' => 'Validation Error',
                'validation_errors' => $validator->messages(),
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
        Muthofun::send($data['mobile_number'], 'Your Product Registered Successfully!');
        // $rechargeAmount = 10;
        // $rechargeService->recharge(
        //     $data['mobile_number'],
        //     $data['mobile_operator'],
        //     $rechargeAmount,
        //     $data['connection_type']
        // );
        return response([
            'response' => 'Product Registered Successfully',
        ], 200);
    }
}
