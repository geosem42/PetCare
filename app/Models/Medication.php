<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medication extends Model
{
    use HasFactory;

	protected $fillable = [
		'pet_id',
		'medication_name',
		'administered_at',
		'dosage',
		'frequency',
		'administering_veterinarian',
		'notes',
	];

	public function pet(): BelongsTo
	{
		return $this->belongsTo(Pet::class);
	}
}