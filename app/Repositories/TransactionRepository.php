<?php

namespace App\Repositories;

use Exception;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Transaction;
use App\Services\Payment\PaymentManager;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Notifications\Subscription\PaymentFailed;
use App\Notifications\Subscription\RenewSuccessfully;
use App\Notifications\Subscription\PaymentSuccessfully;
use App\Notifications\Subscription\RequestSubscription;

class TransactionRepository
{
    use JsonResponser;

    /**
     * Model
     * @var 
     */
    public $model;


    /**
     * Payment manager
     */
    public $paymentManager;

    /**
     * Plan repository
     */
    public $planRepository;

    /**
     * Partner repository
     */
    public $partnerRepository;


    /**
     * Package repository
     */
    public $packageRepository;

    /**
     * User repository
     */
    public $userRepository;

    /**
     * Constructor
     * @param \App\Models\Subscription\Transaction $transaction
     */
    public function __construct(
        Transaction $transaction,
        PaymentManager $paymentManager,
        PlanRepository $planRepository,
        PartnerRepository $partnerRepository,
        PackageRepository $packageRepository,
        UserRepository $userRepository
    ) {
        $this->model = $transaction;
        $this->paymentManager = $paymentManager;
        $this->planRepository = $planRepository;
        $this->partnerRepository = $partnerRepository;
        $this->packageRepository = $packageRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($this->model->transformer);

        // Prepare query
        $data = $this->model->query();

        // Eager loading
        $data->with(['user', 'package', 'partner']);


        // Search 
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Activate the transaction
     * @param string $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activate(string $id)
    {
        $model = $this->model->find($id);

        if (
            !in_array($model->status, [
                config('billing.status.pending.name'),
                config('billing.status.failed.name')
            ])
        ) {
            throw new ReportError("This action is not allowed for the current transaction.", 403);
        }

        // Retrieve the response data of the transaction
        $meta = $model->response;

        //Use Payment manager to resolve driver
        $this->paymentManager->forceActivation($model->payment_method, $meta);

        return $this->message("Transaction activated successfully");
    }

    /**
     * Create a new subscription
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function buy(array $data)
    {
        $customer = auth()->user();

        // Generate transaction code
        $code = $this->generateTransactionCode();

        //Generate metadata
        $plan = $this->planRepository->processPlan(
            $data['plan'],
            $data['billing_period']
        );

        $plan['transaction_code'] = $code;

        //Use payment manager to resolve driver
        $paymentManager = $this->paymentManager->buy(
            $data['payment_method'],
            $plan
        );

        DB::transaction(function () use ($plan, $data, $paymentManager, $customer) {

            //Register package
            $package = $this->packageRepository->create([
                'status' => config("billing.status.pending.name"),
                'is_recurring' => true,
                'transaction_code' => $plan['transaction_code'],
                'user_id' => $customer->id,
                'meta' => $plan, // add plan to the metadata
            ]);

            //Generate transaction
            $transaction = [
                'tax_applied' => config('billing.taxes.enabled'),
                'subtotal' => $paymentManager->amount_subtotal,
                'total' => $paymentManager->amount_total,
                'currency' => $plan['price']['currency'],
                'status' => config("billing.status.pending.name"),
                'payment_method' => $data['payment_method'],
                'billing_period' => $plan['price']['billing_period'],
                'renew' => false,
                'code' => $plan['transaction_code'],
                'response' => $paymentManager->toArray(),
                'package_id' => $package->id,
            ];

            /**
             * Associate a partner to the user's transaction if applicable
             */

            // Check if the authenticated user already has an assigned partner
            if (!empty($partner_id = $customer->partner_id)) {

                // Find the partner by ID
                $partner = $this->partnerRepository->find($partner_id);
                // If the partner exists, associate it with the transaction
                if (!empty($partner)) {
                    $transaction['partner_id'] = $partner->id;
                    $transaction['partner_commission_rate'] = $partner->commission_rate;
                }

                // If the user has no assigned partner, check for a referral code
            } else if (!empty($data['referral_code']) && empty(auth()->user()->partner_id)) {

                // Find the partner by referral code
                $partner = $this->partnerRepository->findByCode($data['referral_code']);

                // If a valid partner is found, associate it with the transaction
                if (!empty($partner)) {
                    $transaction['partner_id'] = $partner->id;
                    $transaction['partner_commission_rate'] = $partner->commission_rate;
                }
            }

            $this->model->create($transaction);
        });

        // Send request notification 
        $customer->notify(new RequestSubscription(route('users.subscriptions.index'), $code));

        return $this->data([
            'data' => [
                "message" => "Redirecting to Stripe Checkout...",
                "redirect_to" => $paymentManager->url,
            ]
        ], 201);
    }

    /**
     * Create new transaction for stripe provider
     * @param \Stripe\PaymentIntent $paymentIntent
     * @param array $data
     * @return Transaction
     */
    public function createStripeRecurringPayment(PaymentIntent $paymentIntent, array $data)
    {
        return $this->model->create([
            'tax_applied' => config('billing.taxes.enabled'),
            'subtotal' => $paymentIntent->amount,
            'total' => $paymentIntent->amount,
            'currency' => $data['meta']['price']['currency'],
            'status' => config("billing.status.pending.name"),
            'billing_period' => $data['meta']['price']['billing_period'],
            'session_id' => null,
            'payment_method' => $data['transaction']['payment_method'],
            'payment_intent_id' => $paymentIntent->id,
            'payment_url' => null,
            'renew' => true,
            'code' => $paymentIntent->metadata->transaction_code,
            'payment_method_id' => $paymentIntent->payment_method,
            'response' => $paymentIntent->toArray(),
            'package_id' => $data['id'],
        ]);
    }

    /**
     * Cancel transaction
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cancel(string $id)
    {
        $model = $this->model->find($id);

        $this->paymentCancelled($model);

        return $this->message(__("Transaction has been cancelled"));
    }

    /**
     * Search transaction by code for the user
     * @param string $code
     * @throws Exception
     * @return array
     */
    public function retrieveTransactionForUser(string $code)
    {
        $transaction = $this->model->with([
            'package',
            'package.user'
        ])->where("code", $code)
            ->whereHas('package.user', function ($query) {
                $query->where('id', auth()->user()->id);
            })->first();

        if (empty($transaction)) {
            throw new Exception("Page not found", 404);
        }

        return $transaction->toArray();
    }

    /**
     * Generate a new session id
     * @return string
     */
    public function generateSessionId()
    {
        return 'cs_offline_' . Str::random(45);
    }

    /**
     * Generate a intent code
     * @return string
     */
    public function generateIntent()
    {
        return 'pi_' . Str::random(45);
    }

    /**
     * Generate a transaction code
     * @return string
     */
    public function generateTransactionCode()
    {
        $micro = explode(' ', microtime());
        $timestamp = date('YmdHis', (int) $micro[1]) . substr($micro[0], 2, 3);
        return 'TXN-' . $timestamp . '-' . strtoupper(Str::random(4));
    }

    /**
     * Set the successfully the operation 
     * @param array $meta
     * @param string $mode
     * @return void
     */
    public function paymentSuccessfully(array $meta, string $mode = 'session')
    {
        // Search transaction
        $transaction = $this->model->where(
            'code',
            $meta['metadata']['transaction_code']
        )->first();

        //Search customer 
        $customer = $this->userRepository->find($meta['metadata']['user_id']);

        //Search auth user
        $auth_user = auth()->user();

        //Page to redirect after payment
        $redirect_to = route('users.checkout.success', [
            "code" => $meta['metadata']['transaction_code']
        ]);

        switch ($mode) {
            case 'session':
                $transaction->payment_intent_id = $meta['payment_intent'];
                $transaction->session_id = $meta['id'];
                $transaction->user_id = $auth_user ? $auth_user->id : null;
                $transaction->push();

                break;

            case "succeed":
                $transaction->payment_method_id = $meta['payment_method'];
                $transaction->payment_intent_id = $meta['payment_intent'];
                $transaction->response = $meta;
                $transaction->status = config('billing.status.successful.name');
                $transaction->payment_url = $meta["receipt_url"];
                $transaction->user_id = $auth_user ? $auth_user->id : null;

                //Dispatch only renew packages
                if ($transaction->renew) {

                    $this->packageRepository->RenewSuccessfully(
                        $transaction->package,
                        $transaction->code
                    );

                    //dispatch notification
                    $customer->notify(new RenewSuccessfully($redirect_to));

                } else {// Dispatch only buy packages
                    $this->packageRepository->paymentSuccessfully($transaction->package);

                    //Dispatch notification
                    $customer->notify(new PaymentSuccessfully($redirect_to));
                }

                //Set the package metadata
                $package_meta = $transaction->package->meta();
                unset($package_meta['transactions']);
                unset($package_meta['transaction']);
                unset($package_meta['user']);

                $transaction->meta = $package_meta;
                $transaction->push();
                break;
            default:
                break;
        }
    }

    /**
     * Set the payment fail
     * @param array $meta
     * @return void
     */
    public function paymentFailed(array $meta)
    {
        $transaction = $this->model->where(
            'code',
            $meta['metadata']['transaction_code']
        )->first();

        //Search  customer 
        $customer = $this->userRepository->find($meta['metadata']['user_id']);

        //Page to redirect after payment
        $redirect_to = route('users.subscriptions.index');

        $transaction->status = config('billing.status.failed.name');
        $transaction->session_id = $meta['session']['id'];
        $transaction->payment_intent_id = $meta['id'];
        $transaction->payment_method_id = $meta['payment_method'];
        $transaction->response = $meta;
        $transaction->payment_url = $meta['session']['url'];

        //Set the package metadata
        $package_meta = $transaction->package->meta();
        unset($package_meta['transactions']);
        unset($package_meta['transaction']);
        unset($package_meta['user']);

        $transaction->meta = $package_meta;
        $transaction->push();

        $customer->notify(new PaymentFailed($redirect_to));
    }

    /**
     * Abort the operation
     * @param \App\Models\Subscription\Transaction $transaction
     * @return void
     */
    public function paymentCancelled(Transaction $transaction)
    {
        $transaction->status = config('billing.status.cancelled.name');
        //Set the package metadata
        $package_meta = $transaction->package->meta();
        unset($package_meta['transactions']);
        unset($package_meta['transaction']);
        unset($package_meta['user']);

        $transaction->meta = $package_meta;
        $transaction->push();

        $this->packageRepository->paymentCancelled($transaction->package);
    }

    /**
     * Set the expiration status to the transaction
     * @param array $meta
     * @return void
     */
    public function paymentExpires(array $meta)
    {
        $transaction = $this->model->where(
            'code',
            $meta['metadata']['transaction_code']
        )->first();

        $transaction->status = config('billing.status.expired.name');
        $transaction->session_id = $meta['id'];
        $transaction->payment_intent_id = $meta['payment_intent'];
        $transaction->payment_url = $meta['url'];
        $transaction->response = $meta;

        // Set expiration status for the package
        $this->packageRepository->paymentExpired($transaction->package);

        $transaction->meta = $transaction->package->meta();
        $transaction->push();
    }

    /**
     * Renew package by user 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renewByUser(Request $request)
    {
        // Search for a package  to renew
        $current_package = $this->packageRepository->find($request->package);
        $this->packageRepository->lastGracePeriodCheck($current_package);
        $package = $current_package->toArray();
        //Generate unique transaction code
        $code = $this->generateTransactionCode();
        $package['meta']['transaction_code'] = $code;

        // New instance of Payment Manager class to use the correct driver
        $paymentManager = $this->paymentManager->buy(
            $request->payment_method,
            $package['meta'] // plan saved
        );

        // Add payment manager inside the package
        $package['payment_manager'] = $paymentManager->toArray();

        //Generate new transaction
        $this->model->create([
            'tax_applied' => config('billing.taxes.enabled'),
            'subtotal' => $package['payment_manager']['amount_subtotal'],
            'total' => $package['payment_manager']['amount_total'],
            'currency' => $package['meta']['price']['currency'],
            'status' => config("billing.status.pending.name"),
            'payment_method' => $request->payment_method,
            'billing_period' => $package['meta']['price']['billing_period'],
            'session_id' => $package['payment_manager']['id'],
            'payment_intent_id' => $package['payment_manager']['payment_intent'],
            'payment_url' => $package['payment_manager']['url'],
            'renew' => true,
            'code' => $code,
            'response' => $package['payment_manager'],
            'package_id' => $package['id'],
        ]);

        // Send request notification 
        auth()->user()->notify(new RequestSubscription(
            route('users.subscriptions.index'),
            $code
        ));

        return $this->data([
            'data' => [
                "message" => "Redirecting to Stripe Checkout...",
                "redirect_to" => $paymentManager->url,
            ]
        ], 201);
    }
}
