<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    protected $guarded = [];
    public function isEmpty()
    {
        return $this->applications()->count() == 0 &&
            $this->redis()->count() == 0 &&
            $this->postgresqls()->count() == 0 &&
            $this->mongodbs()->count() == 0 &&
            $this->services()->count() == 0;
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function postgresqls()
    {
        return $this->hasMany(StandalonePostgresql::class);
    }
    public function redis()
    {
        return $this->hasMany(StandaloneRedis::class);
    }
    public function mongodbs()
    {
        return $this->hasMany(StandaloneMongodb::class);
    }

    public function databases()
    {
        $postgresqls = $this->postgresqls;
        $redis = $this->redis;
        $mongodbs = $this->mongodbs;
        return $postgresqls->concat($redis)->concat($mongodbs);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
        );
    }
}
