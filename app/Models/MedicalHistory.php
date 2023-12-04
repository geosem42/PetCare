<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalHistory extends Model
{
	use HasFactory;

	protected $table = "medical_history";

	protected $fillable = [
		'pet_id',
		'condition',
		'diagnosis_date',
		'treatment',
		'notes',
	];

	public function pet(): BelongsTo
	{
		return $this->belongsTo(Pet::class);
	}
}