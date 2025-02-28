<?php

namespace App\Models\Setting;

use App\Models\Master;
use App\Models\User\User;
use App\Transformers\Setting\TerminalTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Terminal extends Master
{
    use HasFactory;

    public $transformer = TerminalTransformer::class;

    protected $fillable = [
        'command',
        'output',
        'status',
        'user_id'
    ];

    public function whiteList()
    {
        return ['ls', 'git', 'whoami', 'uptime', 'php artisan', 'composer', 'npm'];
    }


    /**
     * Users relations
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
