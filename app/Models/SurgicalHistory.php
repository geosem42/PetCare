<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurgicalHistory extends Model
{
	use HasFactory;

	protected $table = "surgical_history";

	protected $fillable = [
		'pet_id',
		'procedure_name',
		'date',
		'surgeon',
		'notes',
	];

	public function pet(): BelongsTo
	{
		return $this->belongsTo(Pet::class);
	}
}