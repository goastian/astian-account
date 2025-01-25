<?php
namespace App\Models\Subscription;

use App\Models\Master;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Master
{
    use HasFactory;

    /**
     * table name
     * @var string
     */
    public $table = "packages";

    /**
     * Transformer 
     * @var 
     */
    public $transformer = PackageTransformer::class;

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        "user_id",
        'plan_id',
        'price',
        'start_date',
        'end_date',
        'trial_start_at',
        'trial_duration_days',
        'cancellation_date',
        'last_renewal_at',
        'next_payment_due',
        'is_recurring',
        'status',
        'meta',
    ];

    protected $appends = [
        'scope'
    ];

    /**
     * Get the user that owns the UserSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * default status 
     * @return array
     */
    public function status()
    {
        return ['pending', 'successful', 'failed', 'cancelled'];
    }



    /**
     * check status available
     * @param mixed $value
     * @return bool
     */
    public function checkStatus($value)
    {
        return in_array($value, $this->status());
    }

    /**
     * Get the user that owns the scopes
     * @return \Illuminate\Database\Eloquent\Builder|void
     */
    public function getScopeAttribute()
    {
        if ($this->target_type == 'scope') {

            $scope = Scope::with(['service.group', 'role'])->where('id', $this->target_id)
                ->first();
            $service = $scope->service->slug;
            $group = $scope->service->group->slug;
            $role = $scope->role->slug;
            return $this->slug("$group $service $role");
        }
    }
}
