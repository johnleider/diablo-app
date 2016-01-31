<?php

namespace App;

use App\Diablo\Services\HeroService;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'battlenet_hero_id',
        'class', 'gender',
        'type',
        'paragon_level',
        'clan_tag',
        'clan_name',
        'region',
        'name'
    ];

    /**
     * Access Hero API
     *
     * @return Heroes
     */
    public function api()
    {
        return new HeroService($this);
    }

    /**
     * A Hero belongs to a Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * A Hero has many Leaderboards
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class);
    }

    /**
     * A Hero has many solo season records
     *
     * @return mixed
     */
    public function soloSeason()
    {
        return $this->hasMany(Leaderboard::class)
            ->where('season', '=', true)
            ->groupBy('period')
            ->orderBy('rift_level', 'desc');
    }

    /**
     * A Hero belongs to many Items
     *
     * @return $this
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('tool_tip_params');
    }

    /**
     * A Hero has one stats
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stats()
    {
        return $this->hasOne(HeroStat::class);
    }

    /**
     * A Hero belongs to many powers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function powers()
    {
        return $this->belongsToMany(Item::class, 'hero_legendary_power');
    }

    /**
     * A Hero belongs to many skills
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}
