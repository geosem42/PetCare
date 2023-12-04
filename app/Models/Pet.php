<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * @method static create(array $array)
 * @method static orderBy(string $string, string $string1)
 * @method static where(string $string, string $string1, string $string2)
 * @method static findOrFail($id)
 */
class Pet extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'species_id',
		'breed_id',
		'age',
		'gender',
		'client_id',
		'photo'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function species(): BelongsTo
	{
		return $this->BelongsTo(Species::class);
	}

	public function breed(): BelongsTo
	{
		return $this->BelongsTo(Breed::class);
	}

	public function vaccinations(): HasMany
	{
		return $this->hasMany(Vaccination::class);
	}

	public function medicalHistory(): HasMany
	{
		return $this->hasMany(MedicalHistory::class);
	}

	public function medications(): HasMany
	{
		return $this->hasMany(Medication::class);
	}

	public function surgicalHistory(): HasMany
	{
		return $this->hasMany(SurgicalHistory::class);
	}

	public function galleries()
	{
		return $this->hasMany(Image::class);
	}
}