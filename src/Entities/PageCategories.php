<?php

namespace Piclou\Ikcms\Entities;

use Illuminate\Database\Eloquent\Model;
use Piclou\Ikcms\Helpers\Translatable\HasTranslations;
use Ramsey\Uuid\Uuid;

class PageCategories extends Model
{
    use HasTranslations;
    protected $fillable = [
        "id",
        "uuid",
        "published",
        "name",
    ];

    public function translatable()
    {
        return ['name'];
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function Pages()
    {
        return $this->belongsToMany(Pages::class);
    }
}
