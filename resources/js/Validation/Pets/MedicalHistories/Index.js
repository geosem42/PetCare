import { ref, watch } from 'vue'

export const errors = ref({});

export const validateForm = (histories) => {
	errors.value = {};

	// Loop through each history in the form
	for (let i = 0; i < histories.length; i++) {
		const history = histories[i];

		// Validate condition
		if (!history.condition || !history.condition.trim()) {
			errors.value[`histories[${i}].condition`] = 'This field is required.';
		} else if (history.condition.length > 255) {
			errors.value[`histories[${i}].condition`] = 'This field must not exceed 255 characters.';
		}

		// Validate diagnosis_date
		if (!history.diagnosis_date) {
			errors.value[`histories[${i}].diagnosis_date`] = 'This field is required.';
		} else if (!isValidDateFormat(history.diagnosis_date)) {
			errors.value[`histories[${i}].diagnosis_date`] = 'This field must be a valid date.';
		}

		// Validate treatment (optional)
		if (history.treatment && history.treatment.length > 255) {
			errors.value[`histories[${i}].treatment`] = 'This field must not exceed 255 characters.';
		}

		// Validate notes (optional)
		if (history.notes && typeof history.notes !== 'string') {
			errors.value[`histories[${i}].notes`] = 'This field must be a string.';
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
