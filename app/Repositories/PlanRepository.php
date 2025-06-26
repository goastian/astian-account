<?php
namespace App\Repositories;

use App\Transformers\User\UserPlanTransformer;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Assets\Asset;
use Stevebauman\Purify\Facades\Purify;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\Subscription\PlanPriceTransformer;

class PlanRepository implements Contracts
{
    use JsonResponser, Asset;

    /**
     * Model
     * @var Plan
     */
    public $model;

    /**
     * Constructor
     * @param Plan $plan
     */
    public function __construct(Plan $plan)
    {
        $this->model = $plan;
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

        //Prepare query
        $data = $this->model->query();

        // Eager loading
        $data = $data->with([
            'scopes',
            'prices',
            'scopes.role',
            'scopes.service.group'
        ]);

        // Search
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Retrieve the plans for guest users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchPlanForGuest(Request $request)
    {
        $data = $this->model->query();

        // Search plans only active an public
        $data = $data->with(['scopes', 'prices'])
            ->where('active', true);

        // Search by billing period
        if (!empty($billing_period = $request->billing_period)) {
            $data->whereHas('prices', function ($query) use ($billing_period) {
                $query->where('billing_period', $billing_period);
            });
        }

        // Search by service like cloud , vpn , etc
        if (!empty($service_name = $request->service)) {
            $data->whereHas('scopes.service', function ($query) use ($service_name) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($service_name) . '%']);
            });
        }

        $params = $this->filter_transform($this->model->transformer);

        $this->searchByBuilder($data, $params);

        $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, UserPlanTransformer::class);
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser 
     */
    public function create(array $data)
    {
        $plan = DB::transaction(function () use ($data) {

            $plan = $this->model->create([
                'name' => $data['name'],
                'slug' => $this->slug($data['name']),
                'description' => Purify::clean($data['description']),
                'active' => $data['active'],
                'bonus_enabled' => $data['bonus_enabled'] ?? false,
                'bonus_duration' => $data['bonus_duration'] ?? 0,
                'trial_enabled' => $data['trial_enabled'] ?? false,
                'trial_duration' => $data['trial_duration'] ?? 0
            ]);

            // Add scopes
            $plan->scopes()->attach($data['scopes']);

            // Add prices
            foreach ($data['prices'] as $key => $value) {
                $plan->prices()->create([
                    'amount' => $value['amount'],
                    'billing_period' => $value['billing_period'],
                    'currency' => $value['currency']
                ]);
            }

            return $plan;
        });

        return $this->showOne($plan, $this->model->transformer, 201);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Plan
     */
    public function find(string $id)
    {
        return $this->model->with([
            'scopes',
            'prices',
            'scopes.role',
            'scopes.service.group'
        ])->find($id);
    }

    /**
     * Show detail for admin user 
     * @param mixed $plan_id
     * @return JsonResponser
     */
    public function details($plan_id)
    {
        $model = $this->find($plan_id);

        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $plan_id, array $data)
    {
        $plan = $this->find($plan_id);

        DB::transaction(function () use ($plan, $data) {

            $update = false;

            if (!empty($data['name']) && $plan->name != $data['name']) {
                $update = true;
                $plan->name = $data['name'];
            }

            if (!empty($data['description']) && $plan->description != $data['description']) {
                $update = true;
                $plan->description = Purify::clean($data['description']);
            }

            if (!empty('active') && $plan->active != $data['active']) {
                $update = true;
                $plan->active = $data['active'];
            }

            if (!empty($data['trial_enabled']) && $plan->trial_enabled != $data['trial_enabled']) {
                $update = true;
                $plan->trial_enabled = $data['trial_enabled'];
            }

            if (!empty($data['trial_duration']) && $plan->trial_duration != $data['trial_duration']) {
                $update = true;
                $plan->trial_duration = $data['trial_duration'];
            }

            if (!empty($data['bonus_enabled']) && $plan->bonus_enabled != $data['bonus_enabled']) {
                $update = true;
                $plan->bonus_enabled = $data['bonus_enabled'];
            }

            if (!empty($data['bonus_duration']) && $plan->bonus_duration != $data['bonus_duration']) {
                $update = true;
                $plan->bonus_duration = $data['bonus_duration'];
            }

            if ($update) {
                $plan->save();
            }

            if (!empty($data['scopes'])) {
                $plan->scopes()->syncWithoutDetaching($data['scopes']);
            }

            if (!empty($data['prices'])) {
                foreach ($data['prices'] as $key => $value) {

                    $plan->prices()->updateOrCreate(
                        [
                            'billing_period' => strtolower($value['billing_period']),
                        ],
                        [
                            'billing_period' => strtolower($value['billing_period']),
                            'amount' => $value['amount'],
                            'currency' => $value['currency']
                        ]
                    );
                }
            }
        });

        return $this->showOne($plan, $plan->transformer, 200);
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return JsonResponser
     */
    public function delete(string $plan_id)
    {
        DB::transaction(function () use ($plan_id) {

            $plan = $this->find($plan_id);

            $plan->scopes()->detach();

            $plan->prices()->delete();

            $plan->delete();
        });

        return $this->message(__("Plan deleted successfully"), 200);
    }

    /**
     * Delete price of the plan
     * @param string $plan_id
     * @param string $price_id
     * @return JsonResponser
     */
    public function deletePrice(string $plan_id, string $price_id)
    {
        $this->find($plan_id)->prices()->where('id', $price_id)->delete();

        return $this->message(__('Price has been deleted successfully'), 200);
    }

    /**
     * Detach scopes
     * @param string $plan_id
     * @param string $scope_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteScope(string $plan_id, string $scope_id)
    {
        $this->model->find($plan_id)->scopes()->detach($scope_id);

        return $this->message(__('Scopes revoked successfully'), 200);
    }


    /**
     * Details of the plan to save 
     * @param string $billing_period
     * @return array
     */
    public function processPlan(string $plan_id, string $billing_period)
    {
        $plan = $this->model->where('id', $plan_id)->first();

        $price = $plan->prices()->where('billing_period', $billing_period)->first();
        $price = fractal($price, PlanPriceTransformer::class)->toArray()['data'];

        $meta = fractal($plan, $this->model->transformer)->toArray()['data'];

        unset($meta['prices']); //remove prices
        unset($meta['links']); //remove links
        unset($meta['scopes']['links']); //remove links
        unset($price['links']); //remove links
        unset($price['expiration']); //remove links

        //add price to renew plan
        $meta['price'] = $price;

        return $meta;
    }
}
