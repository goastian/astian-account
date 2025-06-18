<?php
namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\User\Partner;
use Illuminate\Http\Request;
use App\Models\Subscription\Transaction;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\Partner\PartnerTransformer;

class PartnerRepository implements Contracts
{
    use JsonResponser;

    /**
     * Model
     * @var 
     */
    public $model;

    /**
     * Transaction model
     * @var Transaction
     */
    public $transaction;

    /**
     * Constructor
     * @param App\Models\User\Partner $partner
     */
    public function __construct(Partner $partner, Transaction $transaction)
    {
        $this->model = $partner;
        $this->transaction = $transaction;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        $params = $this->filter_transform($this->transaction->transformer);

        $data = $this->transaction->query();

        $data = $this->transaction->whereHas(
            'partner',
            function ($query) {
                $query->where('code', auth()->user()->partner->code ?? null);
            }
        );

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $this->transaction->transformer);

        return $this->showAllByBuilder($data, $this->transaction->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {

    }

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Show partner detail
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function details(string $id)
    {
        $partner = $this->find($id)->partner ?? null;
        return !empty($partner) ? $this->showOne($partner, PartnerTransformer::class) : [];
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
     * Search partner by code
     * @param string $code
     */
    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }


    /**
     * Generate code
     * @param mixed $prefix
     * @param mixed $length
     * @return string
     */
    public function generateReferralCode($length = 32)
    {
        $prefix = strtoupper(substr(auth()->user()->name, 0, 2));
        $random = strtoupper(Str::random($length));
        return $prefix . "_" . $random;
    }

    /**
     * Update commission rate
     * @param mixed $percentage
     * @return void
     */
    public function updateCommissionRate(Partner $partner, $percentage)
    {
        $partner->commission_rate = $percentage;
        $partner->push();
    }

    /**
     * Generate the partner link
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generateLink()
    {
        $partner = auth()->user()->partner;

        if (is_null($partner)) {
            $partner = $this->model->create([
                'code' => Partner::generateReferralCode(),
                'user_id' => auth()->user()->id
            ]);
        }

        $partner["links"] = $partner->referLinks();

        return $this->showOne($partner, PartnerTransformer::class, 201);
    }
}
