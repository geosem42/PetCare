import { ref, watch } from 'vue'

export const errors = ref({});

export const validateForm = (vaccinations) => {
	errors.value = {};

	// Loop through each vaccination in the form
	for (let i = 0; i < vaccinations.length; i++) {
		const vaccination = vaccinations[i];

		// Validate vaccine_name
		if (!vaccination.vaccine_name || !vaccination.vaccine_name.trim()) {
			errors.value[`vaccinations[${i}].vaccine_name`] = 'This field is required.';
		} else if (vaccination.vaccine_name.length > 255) {
			errors.value[`vaccinations[${i}].vaccine_name`] = 'This field must not exceed 255 characters.';
		}

		// Validate administered_at
		if (!vaccination.administered_at) {
			errors.value[`vaccinations[${i}].administered_at`] = 'This field is required.';
		  } else if (!isValidDateFormat(vaccination.administered_at)) {
			errors.value[`vaccinations[${i}].administered_at`] = 'This field must be a valid date.';
		  }

		// Validate batch_number (optional)
		if (vaccination.batch_number && vaccination.batch_number.length > 255) {
			errors.value[`vaccinations[${i}].batch_number`] = 'This field must not exceed 255 characters.';
		}

		// Validate administering_veterinarian (optional)
		if (vaccination.administering_veterinarian && vaccination.administering_veterinarian.length > 255) {
			errors.value[`vaccinations[${i}].administering_veterinarian`] = 'This field must not exceed 255 characters.';
		}

		// Validate notes (optional)
		if (vaccination.notes && typeof vaccination.notes !== 'string') {
			errors.value[`vaccinations[${i}].notes`] = 'This field must be a string.';
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