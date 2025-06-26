<?php
namespace App\Http\Controllers\Api\Public;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ServiceRepository;

class PaymentController extends Controller
{

    /**
     * Repository
     * @var 
     */
    public $repository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->repository = $serviceRepository;
    }

    /**
     * Get the billing period
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function billingPeriod()
    {
        return $this->data(['data' => billing_periods()]);
    }

    /**
     * show the status of payments
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function paymentStatus()
    {
        return $this->data(['data' => billing_statuses()]);
    }

    /**
     * methods
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function methods()
    {
        return $this->data(['data' => billing_methods()]);
    }

    /**
     * show the all services
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function services(Request $request)
    {
        return $this->repository->searchForGuest($request);
    }
}
