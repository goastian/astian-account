<?php

namespace App\Console\Commands\Commands\Payment;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription\Package;
use App\Services\Payment\PaymentManager;

class ChargeRecurringPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:charge-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up automatic payments for a customer, allowing future charges to be processed without manual input';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (config('billing.renew.enable')) {

            Log::info('Starting process for recurring payment execution.');

            Package::with([
                'user',
                'transactions',
                'lastTransaction'
            ])->where('is_recurring', true)
                ->whereHas('lastTransaction', function ($query) {
                    $query->whereNotNull('payment_method_id')
                        ->where('payment_method', '!=', config('billing.methods.offline.key'))
                        ->where('status', config('billing.status.successful.name')); //Last successful transaction
                })
                ->whereBetween('end_at', [now(), now()->addHours(intval(config('billing.renew.hours_before', 10)))])
                ->chunk(500, function ($packages) {
                    foreach ($packages as $package) {
                        $data = $package->meta();

                        $paymentManager = new PaymentManager();

                        $paymentManager->chargeRecurringPayment(
                            $data['transaction']['payment_method'],
                            $data
                        );
                    }
                });

            Log::info('Ending process for recurring payment execution.');
        }
    }
}
