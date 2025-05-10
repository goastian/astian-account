<?php
namespace App\Http\Controllers\Web\Account;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Package;
use App\Models\Payment\PaymentManager;
use App\Http\Controllers\WebController;
use App\Models\Subscription\Transaction;
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
        $data = $data->where('user_id', auth()->user()->id);
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
     * @param \App\Models\Payment\PaymentManager $paymentManager
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

        //Generate metadata
        $plan = $plan->processPlan($request->plan, $request->billing_period);

        //Generate payment  
        $paymentManager = $paymentManager->buy($request->payment_method, $plan);

        //Generate transaction code
        $code = Transaction::generateTransactionCode();

        DB::transaction(function () use ($plan, $request, $paymentManager, $code) {

            //Register package
            $package = Package::create([
                'status' => config("billing.status.pending.name"),
                'is_recurring' => false,
                'transaction_code' => $code,
                'user_id' => auth()->id(),
                'meta' => $plan,
            ]);

            $transaction = [
                'tax_applied' => config('billing.taxes.enabled'),
                'subtotal' => $paymentManager->amount_subtotal,
                'total' => $paymentManager->amount_total,
                'currency' => $plan['price']['currency'],
                'status' => config("billing.status.pending.name"),
                'payment_method' => $request->payment_method,
                'billing_period' => $plan['price']['billing_period'],
                'session_id' => $paymentManager->id,
                'payment_intent_id' => $paymentManager->payment_intent,
                'payment_url' => $paymentManager->url,
                'renew' => false,
                'code' => $code,
                'response' => $paymentManager->toArray(),
                'package_id' => $package->id,
            ];

            if (config('billing.taxes.enabled')) {
                $transaction['tax_rate_id'] = null;
                $transaction['tax_percentage'] = null;
                $transaction['tax_amount'] = null;
                $transaction['tax_inclusive'] = null;
            }

            Transaction::create($transaction);


        });

        auth()->user()->notify(new RequestSubscription($code));

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
     * @param \App\Models\Payment\PaymentManager $paymentManager
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
     * @param \App\Models\Payment\PaymentManager $paymentManager
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(Request $request, Package $package, PaymentManager $paymentManager)
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

        $package = $package->find($request->package);

        $paymentManager = $paymentManager->buy(
            $request->payment_method,
            $package->meta
        );

        DB::transaction(function () use ($package, $paymentManager, $request) {

            $transaction = [
                'tax_applied' => config('billing.taxes.enabled'),
                'subtotal' => $paymentManager->amount_subtotal,
                'total' => $paymentManager->amount_total,
                'currency' => $package->meta['price']['currency'],
                'status' => config("billing.status.pending.name"),
                'payment_method' => $request->payment_method,
                'billing_period' => $package->meta['price']['billing_period'],
                'session_id' => $paymentManager->id,
                'payment_intent_id' => $paymentManager->payment_intent,
                'payment_url' => $paymentManager->url,
                'renew' => true,
                'code' => Transaction::generateTransactionCode(),
                'response' => $paymentManager->toArray(),
                'package_id' => $package->id,
            ];

            if (config('billing.taxes.enabled')) {
                $transaction['tax_rate_id'] = null;
                $transaction['tax_percentage'] = null;
                $transaction['tax_amount'] = null;
                $transaction['tax_inclusive'] = null;
            }

            Transaction::create($transaction);
        });

        return $this->data([
            'data' => [
                "message" => "Redirecting to Stripe Checkout...",
                "redirect_to" => $paymentManager->url,
            ]
        ], 201);

    }

    public function success()
    {
        return view('payment.success');
    }
}
