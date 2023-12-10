<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
		'item_name',
		'slug',
		'description',
		'quantity',
		'unit_price'
	];

	protected function performInsert(Builder $query)
    {
        $this->slug = Str::slug($this->item_name) . '-temp';
        parent::performInsert($query);
    }

    protected static function booted()
    {
        static::created(function ($item) {
            $item->slug = Str::slug($item->item_name) . '-' . $item->id;
            $item->save();
        });
    }
}