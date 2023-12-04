<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vaccination extends Model
{
	use HasFactory;

	protected $fillable = [
		'pet_id',
		'vaccine_name',
		'administered_at',
		'batch_number',
		'administering_veterinarian',
		'notes',
	];

	public function pet(): BelongsTo
	{
		return $this->belongsTo(Pet::class);
	}
}