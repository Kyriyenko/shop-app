<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? str($model->{self::getSlugFrom()})->append(time())->slug();
        });
    }

    protected static function getSlugFrom(): string
    {
        return 'title';
    }
}
