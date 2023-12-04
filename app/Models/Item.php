<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}