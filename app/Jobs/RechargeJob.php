<?php

namespace App\Jobs;

use App\Models\Recharge;
use App\Services\RechargeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RechargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Recharge $recharge;

    /**
     * Create a new job instance.
     */
    public function __construct(Recharge $recharge)
    {
        $this->recharge = $recharge;
    }

    /**
     * Execute the job.
     */
    public function handle(RechargeService $rechargeService): void
    {
        $rechargeService->recharge($this->recharge);
    }
}
