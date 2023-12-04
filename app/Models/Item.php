<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Str;

class Item extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
		'item_name',
		'slug',
		'description',
		'quantity',
		'unit_price'
	];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'item_name'
            ]
        ];
    }

    public function save(array $options = [])
    {
        // First, save the model without the slug
        parent::save($options);

        // Then, create a slug including the id and save again
        $this->slug = Str::slug($this->item_name) . '-' . $this->id;
        parent::save();
    }
}