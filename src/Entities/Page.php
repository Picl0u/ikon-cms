<?php

namespace Piclou\Ikcms\Entities;

use Illuminate\Database\Eloquent\Model;
use Piclou\Ikcms\Helpers\Medias\HasMedias;
use Piclou\Ikcms\Helpers\Translatable\HasTranslations;
use Ramsey\Uuid\Uuid;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Page extends Model implements Sortable
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
        "visibility",
        "visibility_password",
        "name",
        "slug",
        "page_category_id",
        "summary",
        "description",
        "image",
        "order",
        "seo_robots",
        "seo_title",
        "seo_description",
        "seo_keywords"
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

    public function Category()
    {
        return $this->belongsTo(PageCategories::class);
    }

}
