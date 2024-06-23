<?php
namespace App\Services;

use App\Enums\ConnectionType;
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

    public function recharge($mobile_no, $operator, $amount = 10, $connection_type = ConnectionType::Prepaid->value)
    {
        $data = [
            'user_id' => $this->user_id,
            'user_key' =>  $this->user_key,
            'unique_id' => $this->unique_id,
            'connection_type' => $connection_type,
            'amount' => $amount,
            'operator' => $operator,
            'mobile_no' => $mobile_no,
        ];
        $response = Http::post($this->url, $data);
        return $response->status();
    }
}