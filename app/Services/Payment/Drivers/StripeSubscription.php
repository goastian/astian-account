<?php
namespace App\Services\Payment\Drivers;

use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use App\Models\Subscription\Transaction;
use App\Repositories\TransactionRepository;
use Stripe\Exception\InvalidRequestException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Services\Payment\Contracts\PaymentMethod;

class StripeSubscription implements PaymentMethod
{

    /**
     * Provider name
     * @var string
     */
    protected $method = "stripe_checkout";


    /**
     * Repository
     */
    public $repository;

    /**
     * Billing period supported by stripe
     * @var array
     */
    protected $stripe_billing_period = [
        'daily' => ['interval' => 'day', 'interval_count' => 1],
        'weekly' => ['interval' => 'week', 'interval_count' => 1],
        'biweekly' => ['interval' => 'week', 'interval_count' => 2],
        'monthly' => ['interval' => 'month', 'interval_count' => 1],
        'quarterly' => ['interval' => 'month', 'interval_count' => 3],
        'semiannual' => ['interval' => 'month', 'interval_count' => 6],
        'annual' => ['interval' => 'year', 'interval_count' => 1],
        'biannual' => ['interval' => 'year', 'interval_count' => 2]
    ];

    public function __construct(TransactionRepository $transactionRepository)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->repository = $transactionRepository;
    }

    /**
     * Process data
     * @param array $data
     * @return Session
     */
    public function buy(array $data)
    {
        $user = $this->createCustomerId();

        //Create metadata 
        $meta = [
            'mode' => 'payment',
            'customer' => $user->stripe_customer_id,
            'expires_at' => now()->addMinutes(30)->timestamp,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $data['price']['currency'],
                        'unit_amount' => $data['price']['amount'],
                        'product_data' => [
                            'name' => $data['name'],
                            'description' => strip_tags($data['description']),
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session',
                'metadata' => [
                    'user_id' => $user->id,
                    'transaction_code' => $data['transaction_code'],
                ],
            ],
            'success_url' => route('users.checkout.success') . "?code={$data['transaction_code']}",
            'metadata' => [
                'user_id' => $user->id,
                'transaction_code' => $data['transaction_code'],
            ],
        ];

        try {
            $session = Session::create($meta);
            return $session;

        } catch (InvalidRequestException $th) {
            throw new ReportError($th->getMessage(), 403);
        }
    }

    /**
     * Charge recurring payment
     * @param array $package
     * @return void
     */
    public function chargeRecurringPayment(array $package)
    {
        //Generate new transaction code
        $code = $this->repository->generateTransactionCode();

        //Generate a new payment intent to renew package
        $intent = PaymentIntent::create([
            'amount' => $package['meta']['price']['amount'],
            'currency' => strtolower($package['meta']['price']['currency']),
            'customer' => $package["user"]["stripe_customer_id"],
            'payment_method' => $package['transaction']['payment_method_id'],
            'off_session' => true,
            'confirm' => true,
            'metadata' => [
                'transaction_code' => $code,
                'user_id' => $package['user']['id'],
                'renew' => true
            ],
        ]);

        //Create new transaction for this payment intent
        $this->repository->createStripeRecurringPayment($intent, $package);
    }

    /**
     * Abort operation
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction)
    {

    }

    /**
     * force activation
     * @param array $response
     * @return void
     */
    public function forceActivation(array $response)
    {
        $session = Session::retrieve($response['id']);

        if ($session->payment_status != "paid") { //check the session has been paid
            throw new ReportError("Payment not successful. Status: " . $session->payment_status, 402);
        }

        // Retrieve the payment intent of the session
        $payment_intent = PaymentIntent::retrieve($session->payment_intent);

        if ($payment_intent->status != "succeeded") {//check the status has been succeeded
            throw new ReportError("Payment not successful. Status: " . $session->status, 402);
        }

        // Retrieve the last charge succeeded
        $last_charge = Charge::retrieve($payment_intent->latest_charge);

        $this->repository->paymentSuccessfully($last_charge->toArray(), 'succeed');
    }

    /**
     * Add customer id to the current user
     * @return \App\Models\User
     */
    public function createCustomerId()
    {
        $user = auth()->user();
        if (!$user->stripe_customer_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);

            $user->stripe_customer_id = $customer->id;
            $user->save();
        }

        return $user;
    }
}
