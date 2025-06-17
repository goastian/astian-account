<?php
namespace App\Http\Controllers\Web\Account;

use App\Notifications\Subscription\AutoPaymentSuccess;
use Exception;
use Inertia\Inertia;
use App\Models\User\Partner;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Package;
use App\Http\Controllers\WebController;
use App\Models\Subscription\Transaction;
use App\Services\Payment\PaymentManager;
use App\Transformers\User\UserPackageTransformer;
use App\Notifications\Subscription\RequestSubscription;

class UserSubscriptionController extends WebController
{

    /**
     * Show the index component
     * @param \App\Models\Subscription\Package $package
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Package $package)
    {
        $data = $package->query();
        $data = $data->with([
            'lastTransaction',
            'transactions',
            'user'
        ])->where('user_id', auth()->user()->id);

        $params = $this->filter_transform(UserPackageTransformer::class);
        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, UserPackageTransformer::class);


        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, UserPackageTransformer::class);
        }

        return Inertia::render(
            "Account/Subscription/Index",
            [
                'packages' => $this->showAllByBuilderArray($data, UserPackageTransformer::class)
            ]
        );
    }


    /**
     * Buy subscription
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Plan $plan
     * @param \App\Services\Payment\PaymentManager $paymentManager
     * @param \App\Models\Subscription\Transaction $transaction
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function buy(Request $request, Plan $plan, PaymentManager $paymentManager, Transaction $transaction)
    {
        $request->validate([
            'plan' => ['required', 'exists:plans,id'],
            'billing_period' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null(billing_get_period($value))) {
                        $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                    }
                }
            ],
            'payment_method' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null(billing_get_method($value))) {
                        $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                    }
                }
            ],
        ]);

        //Generate unique transaction code
        $code = Transaction::generateTransactionCode();

        //Generate metadata
        $plan = $plan->processPlan($request->plan, $request->billing_period);

        $plan['transaction_code'] = $code;

        //Generate payment
        $paymentManager = $paymentManager->buy($request->payment_method, $plan);

        DB::transaction(function () use ($plan, $request, $paymentManager, $code) {

            //Register package
            $package = Package::create([
                'status' => config("billing.status.pending.name"),
                'is_recurring' => true,
                'transaction_code' => $code,
                'user_id' => auth()->user()->id,
                'meta' => $plan,
            ]);

            //Generate transaction
            $transaction = [
                'tax_applied' => config('billing.taxes.enabled'),
                'subtotal' => $paymentManager->amount_subtotal,
                'total' => $paymentManager->amount_total,
                'currency' => $plan['price']['currency'],
                'status' => config("billing.status.pending.name"),
                'payment_method' => $request->payment_method,
                'billing_period' => $plan['price']['billing_period'],
                'renew' => false,
                'code' => $code,
                'response' => $paymentManager->toArray(),
                'package_id' => $package->id,
            ];

            /**
             * Associate a partner to the user's transaction if applicable
             */

            // Check if the authenticated user already has an assigned partner
            if (!empty($partner_id = auth()->user()->partner_id)) {

                // Find the partner by ID
                $partner = Partner::find($partner_id);
                // If the partner exists, associate it with the transaction
                if (!empty($partner)) {
                    $transaction['partner_id'] = $partner->id;
                    $transaction['partner_commission_rate'] = $partner->commission_rate;
                }

                // If the user has no assigned partner, check for a referral code
            } else if ($request->referral_code && empty(auth()->user()->partner_id)) {

                // Find the partner by referral code
                $partner = Partner::where('code', $request->referral_code)->first();

                // If a valid partner is found, associate it with the transaction
                if (!empty($partner)) {
                    $transaction['partner_id'] = $partner->id;
                    $transaction['partner_commission_rate'] = $partner->commission_rate;
                }
            }

            Transaction::create($transaction);
        });

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

    /**
     * Abort operation
     * @param string $transaction_id
     * @param \App\Services\Payment\PaymentManager $paymentManager
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cancel(string $transaction_id, PaymentManager $paymentManager)
    {
        $transaction = Transaction::find($transaction_id);

        Transaction::paymentCancelled($transaction);

        return $this->message(__("Transaction has been cancelled"));
    }

    /**
     * renew package
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Package $package
     * @param \App\Services\Payment\PaymentManager $paymentManager
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(Request $request, PaymentManager $paymentManager)
    {
        $request->validate([
            'package' => ['required', 'exists:packages,id'],
            'payment_method' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null(billing_get_method($value))) {
                        $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                    }
                }
            ],
        ]);

        // Search for a package  to renew
        $data = Package::with([
            'user',
            'transactions',
            'lastTransaction'
        ])->find($request->package);


        $data->lastGracePeriodCheck();

        $package = $data->toArray();

        //Generate unique transaction code
        $code = Transaction::generateTransactionCode();
        $package['meta']['transaction_code'] = $code;

        // New instance of Payment Manager class to use the correct driver
        $paymentManager = $paymentManager->buy(
            $request->payment_method,
            $package['meta'] // plan saved
        );

        // Add payment manager inside the package
        $package['payment_manager'] = $paymentManager->toArray();

        //Generate new transaction
        DB::transaction(function () use ($package, $request, $code) {
            Transaction::create([
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
        });

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

    /**
     * Show the transaction
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function success(Request $request)
    {
        $transaction = Transaction::with([
            'package',
            'package.user'
        ])->where("code", $request->code)
            ->whereHas('package.user', function ($query) {
                $query->where('id', auth()->user()->id);
            })->first();

        if (empty($transaction)) {
            throw new Exception("Page not found", 404);
        }

        return view('payment.success', ['transaction' => $transaction->toArray()]);
    }
}
