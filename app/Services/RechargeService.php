<?php
namespace App\Services;

use App\Enums\ConnectionType;
use App\Enums\RechargeStatus;
use App\Models\Recharge;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RechargeService
{
    protected $url = 'https://aamarapp.xyz/rechargeapi/api/v1/api.php';
    protected $user_id = '';
    protected $user_key = '';
    protected $unique_id = '';

    public function __construct()
    {
        if (config('recharge.user_id') == null) {
            throw new \Exception('Recharge User Id is not set');
        }
        if (config('recharge.user_key') == null) {
            throw new \Exception('Recharge User Key is not set');
        }

        $this->user_id = config('recharge.user_id');
        $this->user_key = config('recharge.user_key');
        $this->unique_id = $this->generateUniqueId();
    }

    protected function generateUniqueId()
    {
        return Str::random(15);
    }

    public function recharge(Recharge $recharge)
    {
        $data = [
            'user_id' => $this->user_id,
            'user_key' =>  $this->user_key,
            'unique_id' => $this->unique_id,
            'mobile_no' => $recharge->mobile_no,
            'operator' => $recharge->mobile_operator,
            'connection_type' => $recharge->connection_type,
            'amount' => $recharge->amount,
        ];
        $response = Http::post($this->url, $data);
        $response = $response->object();
        $recharge->update([
            'unique_id' => $this->unique_id,
            'status' => $response->status == 200 ? RechargeStatus::Submitted : RechargeStatus::Failed,
            'status_code' => $response->status,
            'status_description' => $response->results,
        ]);
    }
}