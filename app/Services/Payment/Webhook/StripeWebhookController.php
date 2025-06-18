<?php
namespace App\Services\Payment\Webhook;

use App\Repositories\TransactionRepository;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Subscription\Transaction;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param \App\Repositories\TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }

    /**
     * Listen events form stripe provider
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error($e);
            http_response_code(400);
            exit();
        } catch (SignatureVerificationException $e) {
            Log::error($e);
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $metadata = $event->data->object->toArray();
                $this->repository->paymentSuccessfully($metadata);
                break;

            case 'payment_intent.payment_failed':
                $metadata = $event->data->object->toArray();
                $sessions = Session::all([
                    'limit' => 1,
                    'payment_intent' => $metadata['id'],
                ]);

                $metadata['session'] = $sessions->data[0]->toArray();
                $this->repository->paymentFailed($metadata);
                break;

            case "checkout.session.expired":
                $metadata = $event->data->object->toArray();
                $this->repository->paymentExpires($metadata);
                break;

            case "charge.succeeded":
                $metadata = $event->data->object->toArray();
                $this->repository->paymentSuccessfully($metadata, 'succeed');
            default:
                Log::info("Listen unknown event : ", $event->toArray());
        }

        return response()->json(['status' => 'success']);
    }
}
