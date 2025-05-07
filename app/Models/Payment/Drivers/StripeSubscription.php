<?php
namespace App\Models\Payment\Drivers;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session;
use App\Models\Subscription\Transaction;
use App\Models\Payment\Contracts\PaymentMethod;
use Stripe\TaxRate;

class StripeSubscription implements PaymentMethod
{

    protected $method = "stripe_checkout";

    /**
     * Process data
     * @param array $data
     * @return Session
     */
    public function process(array $data)
    {
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        if (config('billing.taxes.enabled')) {
            $tax = TaxRate::create([
                'display_name' => config('billing.taxes.name'),
                'description' => config('billing.taxes.description'),
                'jurisdiction' => config('billing.taxes.jurisdiction'),
                'percentage' => config('billing.taxes.percentage'),
                'inclusive' => config('billing.taxes.exclusive', false),
            ]);

            settingAdd('billing.taxes.id', $tax->id);
        }

        // Create customer if not exists
        if (!$user->stripe_customer_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);

            $user->stripe_customer_id = $customer->id;
            $user->save();
        }

        //Create metadata 
        $meta = [
            'customer' => $user->stripe_customer_id,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $data['price']['currency'],
                        'unit_amount' => $data['price']['cents'],
                        'product_data' => [
                            'name' => $data['name'],
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('users.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'user_id' => $user->id,
                'plan_id' => $data['id'],
            ],
        ];

        // Add tax if enabled
        if (config('billing.taxes.enabled') && !is_null(config('billing.taxes.id'))) {

            foreach ($meta['line_items'] as &$item) {
                $item['tax_rates'] = [config('billing.taxes.id')];
            }
        }

        $session = Session::create($meta);
        return $session;
    }


    /**
     * Abort operation
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction)
    {

    }

}
