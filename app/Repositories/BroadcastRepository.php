<?php
namespace App\Repositories;

use App\Support\CacheKeys;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Broadcasting\Broadcast;
use Illuminate\Database\QueryException;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\Broadcast as Broadcasting;


class BroadcastRepository implements Contracts
{

    use JsonResponser;

    /**
     * Instance of the model
     * @var 
     */
    public $model;

    public function __construct(Broadcast $broadcast)
    {
        $this->model = $broadcast;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        $params = $this->filter_transform($this->model->transformer);

        // Prepare query
        $data = $this->model->query();

        // Search 
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $params);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        $model = $this->model->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'slug' => Str::slug($data['name'], '-'),
            'system' => $data['system']
        ]);

        Cache::forget(CacheKeys::broadcast());

        return $this->showOne($model, $this->model->transformer, 201);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {

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
     * @return JsonResponser
     */
    public function delete(string $id)
    {
        $model = $this->model->find($id);

        throw_if($model->system, new ReportError(__("This service cannot be deleted because it is a system service."), 403));

        collect(Broadcast::channelsByDefault())->map(function ($value, $key) use ($model) {
            if ($value == $model->channel) {
                throw new ReportError(__("This action cannot be completed because this channel is a system channel and cannot be deleted."), 400);
            }
        });

        $model->delete();

        Cache::forget(CacheKeys::broadcast());

        return $this->showOne($model, $this->model->transformer);
    }


    /**
     * Register the all channels available in the system
     * @return void
     */
    public static function register()
    {
        try {

            $cacheKey = CacheKeys::broadcast();

            if (Cache::has($cacheKey)) {
                $channels = Cache::get($cacheKey);

            } else {
                $channels = Broadcast::all();

                if (!empty($channels)) {
                    Cache::put($cacheKey, $channels, now()->addDays(intval(config('cache.expires', 90))));
                }
            }

            foreach ($channels as $broadcast) {

                /**
                 * Register private channels
                 */
                Broadcasting::channel($broadcast->channel . ".{id}", function ($user, $id) {
                    return (int) $user->id === (int) $id;
                });

                /**
                 * Register public channels
                 */
                Broadcasting::channel($broadcast->channel, function ($user) {
                    return (int) $user->id === (int) request()->user()->id;
                });

            }
            ;
        } catch (QueryException $e) {
        }
    }
}
