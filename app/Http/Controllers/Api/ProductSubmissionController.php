<?php

namespace App\Http\Controllers\Api;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use App\Http\Controllers\Controller;
use App\Jobs\RechargeJob;
use App\Models\Customer;
use App\Models\Recharge;
use App\Models\Unit;
use Devfaysal\Muthofun\Facades\Muthofun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'regex:/^[a-zA-Z ]*$/'],
            'serial' => ['required', 'numeric', Rule::exists('units', 'serial')->whereNull('registered_at')],
            'mobile_number' => ['required', 'digits:11', 'phone:BD'],
            'mobile_operator' => ['required', Rule::enum(MobileOperator::class)],
            'connection_type' => ['required', Rule::enum(ConnectionType::class)],
        ];
        $messages = [
            'name.required' => 'আপনার নাম লিখুন',
            'serial.required' => 'সিরিয়াল নাম্বার লিখুন',
            'mobile_number.required' => 'মোবাইল নাম্বার লিখুন',
            'mobile_number.digits' => 'মোবাইল নাম্বার অবশ্যই ১১ ডিজিটের হতে হবে',
            'mobile_number.phone' => 'মোবাইল নাম্বার অবশ্যই বাংলাদেশী হতে হবে',
            'mobile_operator.required' => 'অপারেটর নির্বাচন করুন',
            'connection_type.required' => 'সিমের ধরণ নির্বাচন করুন',
            'name.regex' => 'আপনার নাম ইংরেজী অক্ষরে লিখুন',
            'serial.numeric' => 'সিরিয়াল নাম্বার অবশ্যই নাম্বার হতে হবে',
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

        $message = 'Dear '.$data['name'].', Your Product ('.$data['serial'].') Registered Successfully!';
        Muthofun::send($data['mobile_number'], $message);

        if ($unit->rechargeGroup) {
            $recharge = Recharge::create([
                'unit_id' => $unit->id,
                'mobile_no' => $customer->mobile_number,
                'mobile_operator' => $customer->mobile_operator,
                'connection_type' => $customer->connection_type,
                'amount' => $unit->rechargeGroup->amount,
            ]);
            RechargeJob::dispatch($recharge);
        }

        return response([
            'response' => 'Product Registered Successfully',
        ], 200);
    }
}
