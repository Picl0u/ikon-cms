<?php

namespace Piclou\Ikcms\Entities;

use Illuminate\Database\Eloquent\Model;
use Piclou\Ikcms\Helpers\Medias\HasMedias;
use Piclou\Ikcms\Helpers\Translatable\HasTranslations;
use Ramsey\Uuid\Uuid;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Slider extends Model implements Sortable
{
    use SortableTrait;
    use HasMedias;
    use HasTranslations;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        "id",
        "uuid",
        "published",
        "name",
        "description",
        "image",
        "video",
        "link",
        "position",
        "order"
    ];

    public function translatable()
    {
        return ['name','description'];
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
