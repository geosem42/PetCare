import { ref, watch } from 'vue'

export const errors = ref({});

export const validateForm = (medications) => {
	errors.value = {};

	// Loop through each medication in the form
	for (let i = 0; i < medications.length; i++) {
		const medication = medications[i];

		// Validate medication_name
		if (!medication.medication_name || !medication.medication_name.trim()) {
			errors.value[`medications[${i}].medication_name`] = 'This field is required.';
		} else if (medication.medication_name.length > 255) {
			errors.value[`medications[${i}].medication_name`] = 'This field must not exceed 255 characters.';
		}

		// Validate administered_at
		if (!medication.administered_at) {
			errors.value[`medications[${i}].administered_at`] = 'This field is required.';
		} else if (!isValidDateFormat(medication.administered_at)) {
			errors.value[`medications[${i}].administered_at`] = 'This field must be a valid date.';
		}

		// Validate dosage (optional)
		if (medication.dosage && medication.dosage.length > 255) {
			errors.value[`medications[${i}].dosage`] = 'This field must not exceed 255 characters.';
		}

		// Validate frequency (optional)
		if (medication.frequency && medication.frequency.length > 255) {
			errors.value[`medications[${i}].frequency`] = 'This field must not exceed 255 characters.';
		}

		// Validate administering_veterinarian (optional)
		if (medication.administering_veterinarian && medication.administering_veterinarian.length > 255) {
			errors.value[`medications[${i}].administering_veterinarian`] = 'This field must not exceed 255 characters.';
		}

		// Validate notes (optional)
		if (medication.notes && typeof medication.notes !== 'string') {
			errors.value[`medications[${i}].notes`] = 'This field must be a string.';
		}
	}
};

export const clearError = (field) => {
	if (errors.value[field]) {
		errors.value[field] = '';
	}
};

export const watchFields = (form) => {
	Object.keys(form).forEach(field => {
		watch(() => form[field], () => {
			clearError(field);
		});
	});
};

function isValidDateFormat(dateString) {
  const regex = /^\d{4}-\d{2}-\d{2}$/;
  return regex.test(dateString);
}
