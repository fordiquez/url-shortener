<?php

namespace App\Models;

use App\Parsers\UrlParser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Url extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'short_urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'code',
        'expires_at',
        'user_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];

    /**
     * Boot the model.
     *
     * @return  void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($url) {
            app()->make(UrlParser::class)->setUrlInfos($url);
        });

        static::updating(function ($url) {
            app()->make(UrlParser::class)->setUrlInfos($url);
        });
    }

    public function couldExpire(): bool
    {
        return $this->expires_at !== null;
    }

    /**
     * Return whether an url has expired.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        if (! $this->couldExpire()) {
            return false;
        }

        $expiresAt = new Carbon($this->expires_at);

        return ! $expiresAt->isFuture();
    }

    /**
     * Get the user that created the url.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        $provider = config('auth.guards.api.provider');

        return $this->belongsTo(config("auth.providers.{$provider}.model"));
    }
}
