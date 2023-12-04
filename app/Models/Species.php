<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, string $string1, string $string2)
 */
class Species extends Model
{
    use HasFactory;

	protected $table = 'species';

	protected $fillable = [
		'name',
	];

	public function pet(): BelongsTo
	{
		return $this->belongsTo(Pet::class);
	}

	public function breeds(): HasMany
	{
		return $this->hasMany(Breed::class);
	}
}