<?php

namespace App\Models;

use Attribute;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'cover'];
    protected $appends = ['links'];

    public function seasons() {

        return $this->hasMany(Season::class, 'series_id'); // 1 para muitos (1 serie tem varias temporadas)
    }

    public function episodes() {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    protected static function booted(){

        self::addGlobalScope('ordered', function(Builder $queryBuilder){
            $queryBuilder->orderBy('nome');
        });
    }

    public function getLinksAttribute()
    {
        return [
            [
                'rel' => 'self',
                'url' => "/api/series/{$this->id}"
            ],
            [
                'rel' => 'seasons',
                'url' => "/api/series/{$this->id}/seasons"
            ],
            [
                'rel' => 'episodes',
                'url' => "/api/series/{$this->id}/episodes"
            ],
        ];
    }

}
