<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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

	protected function performInsert(Builder $query)
	{
		$this->slug = Str::slug($this->name) . '-temp';
		parent::performInsert($query);
	}

	protected static function booted()
	{
		static::created(function ($pet) {
			$pet->slug = Str::slug($pet->name) . '-' . $pet->id;
			$pet->save();
		});
	}

	public function client(): BelongsTo
	{
		return $this->belongsTo(Client::class);
	}

	public function species(): BelongsTo
	{
		return $this->BelongsTo(Species::class);
	}

	public function breed(): BelongsTo
	{
		return $this->BelongsTo(Breed::class);
	}

	/* 	public function vaccinations(): HasMany
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

		public function images()
		{
			return $this->hasMany(Image::class);
		} */
}