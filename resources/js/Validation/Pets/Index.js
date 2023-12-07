import { ref, watch } from 'vue'

export const errors = ref({});

export const validateForm = (form) => {
	// Clear previous errors
	errors.value = {};

	// If form is undefined, return early
	if (!form) {
			return;
	}

	// Validate name (trimming whitespace)
	if (!form.name.trim()) {
			errors.value.name = 'This field is required';
	}

	// Validate client_id (trimming whitespace if it's a string)
	if (!form.client_id) {
			errors.value.client_id = 'This field is required';
	} else if (typeof form.client_id === 'string' && !form.client_id.trim()) {
			errors.value.client_id = 'This field cannot be only spaces';
	} else if (typeof form.client_id !== 'number') {
			errors.value.client_id = 'This field must be an integer';
	}

	// Validate species_id (trimming whitespace if it's a string)
	if (!form.species_id) {
			errors.value.species_id = 'This field is required';
	} else if (typeof form.species_id === 'string' && !form.species_id.trim()) {
			errors.value.species_id = 'This field cannot be only spaces';
	} else if (typeof form.species_id !== 'number') {
			errors.value.species_id = 'This field must be an integer';
	}

	// Validate breed_id
	if (form.breed_id !== null && form.breed_id !== '' && typeof form.breed_id !== 'number') {
			errors.value.breed_id = 'This field must be an integer';
	} else if (form.breed_id === null || form.breed_id === '') {
			delete errors.value.breed_id;
	}

	// Validate age
	if (form.age !== null && form.age !== '' && typeof form.age !== 'number') {
			errors.value.age = 'This field must be an integer';
	} else if (form.age === null || form.age === '') {
			delete errors.value.age;
	}

	// Validate gender
	if (form.gender !== null && typeof form.gender !== 'string') {
			errors.value.gender = 'This field must be a string';
	} else if (form.gender === null || form.gender === '') {
			delete errors.value.gender;
	} else if (form.gender && !form.gender.trim()) {
			errors.value.gender = 'This field cannot be only spaces';
	}

	// Validate photo
	if (form.photo && form.photo.file && !form.photo.file.type.match('image.*')) {
			errors.value.photo = 'This field must be an image file';
	} else if (!form.photo || !form.photo.file) {
			delete errors.value.photo;
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