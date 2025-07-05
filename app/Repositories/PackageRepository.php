<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Subscription\Package;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\User\UserPackageTransformer;

class PackageRepository implements Contracts
{
    use JsonResponser;
    /**
     * Model
     * @var 
     */
    public $model;

    /**
     * User repository
     * @var 
     */
    public $userRepository;

    /**
     * Constructor
     * @param \App\Models\Subscription\Package $package
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(Package $package, UserRepository $userRepository)
    {
        $this->model = $package;
        $this->userRepository = $userRepository;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function search(Request $request)
    {

    }


    public function searchForUser(Request $request)
    {
        // Prepare query
        $data = $this->model->query();

        $data = $data->with([
            'lastTransaction',
            'transactions',
            'user'
        ])->where(
                'user_id',
                auth()->user()->id
            )->orderByDesc('created_at');


        $params = $this->filter_transform(UserPackageTransformer::class);

        $data = $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, UserPackageTransformer::class);
    }

    /**
     * Create new resource
     * @param array $data
     * @return Package
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search specific resource by id
     * @param string $id
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>>|Package|TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->model->with([
            'user',
            'transactions',
            'lastTransaction'
        ])->find($id);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {

    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {

    }

    /**
     * Determine if the package renewal is still possible.
     * @param \App\Models\Subscription\Package $model
     * @throws  ReportError
     * @return void
     */
    public function lastGracePeriodCheck(Package $model)
    {
        $grace_period_days = intval(config('billing.renew.grace_period_days', 5));

        $last_day = $model->end_at->addDays($grace_period_days);

        if (now() > $last_day) {
            throw new ReportError(__("Renewal Failed: The request cannot be processed because the renewal date has already passed. Please contact support for further assistance."), 400);
        }
    }

    /**
     * Determine the expiration date of the package
     * @param \App\Models\Subscription\Package $package
     */
    public function getEndDate(Package $package)
    {
        // Retrieve plan metadata
        $meta = $package->meta;

        // Set the initial end date to the existing value or use the current date/time if not set
        $end_date = $package->end_at ?? now();

        // If this is the initial purchase (no end_at set)
        if (empty($package->end_at)) {

            // If a trial is enabled and has a duration, add it to the end date
            if ($meta['trial_enabled'] && $meta['trial_duration']) {
                $end_date->addDays($meta['trial_duration']);
            }

            // If a bonus is enabled and has a positive duration, add it to the end date
            if ($meta['bonus_enabled'] && $meta['bonus_duration'] > 0) {
                $end_date->addDays($meta['bonus_duration']);
            }
        }

        // If the subscription is being renewed (end_at is set)
        if (!empty($package->end_at)) {

            // Calculate the last valid day for renewal (grace period)
            $last_day = $package->end_at->addDays(config('billing.renew.grace_period_days'));

            // If we're within the renewal grace period and bonuses are enabled
            if (
                $last_day > now() && // still within the renewal window
                config("billing.renew.bonus_enabled") && // bonus on renewals is enabled globally
                $meta['bonus_enabled'] && // bonus is enabled in the plan
                $meta['bonus_duration'] > 0 // bonus duration is a positive number
            ) {
                $end_date->addDays($meta['bonus_duration']);
            }
        }

        // Finally, add the billing period duration to the end date
        $period = config('billing.period.' . $meta['price']['billing_period']);
        $unit = $period['unit']; // e.g., 'days', 'months'
        $interval = $period['interval']; // e.g., 1, 3, 6

        return $end_date->{"add" . ucfirst($unit)}($interval);
    }

    /**
     * Add payments scopes to the user
     * @return void
     */
    public function addOrUpdatedScopeSubscription(Package $package)
    {
        $user = $this->userRepository->find($package->user_id);

        $scopes = $package->meta['scopes'];

        foreach ($scopes as $key => $value) {
            $user->userScopes()->updateOrCreate(
                [
                    'scope_id' => $value['id'],
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                ],
                [
                    'scope_id' => $value['id'],
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'end_date' => $package->end_at,
                ]
            );
        }
    }

    /**
     * Set payment successfully for the package
     * @param \App\Models\Subscription\Package $package
     * @return void
     */
    public function paymentSuccessfully(Package $package)
    {
        $package->start_at = now();
        $package->end_at = $this->getEndDate($package);
        $package->status = config('billing.status.successful.name');
        $package->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription($package);
    }

    /**
     * Set the renewal successfully for the package
     * @param \App\Models\Subscription\Package $package
     * @param string $transaction_code
     * @return void
     */
    public function renewSuccessfully(Package $package, string $transaction_code)
    {
        $package->end_at = $this->getEndDate($package);
        $package->status = config('billing.status.successful.name');
        $package->last_renewal_at = now();
        $package->transaction_code = $transaction_code;
        $package->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription($package);
    }

    /**
     *  Set failed to the package
     * @param \App\Models\Subscription\Package $package
     * @return void
     */
    public function paymentFailed(Package $package)
    {
        $package->status = config('billing.status.failed.name');
        $package->push();
    }

    /**
     * Cancel operation
     * @param \App\Models\Subscription\Package $package
     * @return void
     */
    public function paymentCancelled(Package $package)
    {
        $package->status = config('billing.status.cancelled.name');
        $package->cancellation_at = now();
        $package->push();
    }

    /**
     * Set expires for the package
     * @return void
     */
    public function paymentExpired(Package $package)
    {
        $package->status = config('billing.status.expired.name');
        $package->push();
    }

    /**
     * Enable or disable recurring payment by package
     * @param string $package_id
     * @return JsonResponser
     */
    public function recurringPaymentEnableOrDisable(string $package_id)
    {
        $package = $this->find($package_id);

        if ($package->user_id != auth()->user()->id) {
            throw new ReportError(__('Resource cannot be found'), 404);
        }

        $package->is_recurring = !$package->is_recurring;

        $package->push();

        return $this->message(__('Recurring payment for this package has been updated successfully'));
    }
}
