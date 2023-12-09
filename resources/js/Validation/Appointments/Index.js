import { ref, watch } from 'vue'

export const errors = ref({});

export const validateForm = (form) => {
    // Clear previous errors
    errors.value = {};

    // If form is undefined, return early
    if (!form) {
        return;
    }

    // Validate title (trimming whitespace)
    if (!form.title.trim()) {
        errors.value.title = 'This field is required';
    } else {
        delete errors.value.title;
    }

    // Validate client_id (checking if it's an integer)
    if (!form.client_id) {
        errors.value.client_id = 'This field is required';
    } else if (typeof form.client_id !== 'number') {
        errors.value.client_id = 'This field must be an integer';
    } else {
        delete errors.value.client_id;
    }

    // Validate start_time (checking if it's a valid date)
    if (!form.start_time || isNaN(Date.parse(form.start_time))) {
        errors.value.start_time = 'This field is required';
    } else {
        delete errors.value.start_time;
    }

    // Validate end_time (checking if it's a valid date and after start_time)
    if (!form.end_time || isNaN(Date.parse(form.end_time))) {
        errors.value.end_time = 'This field is required';
    } else if (Date.parse(form.end_time) <= Date.parse(form.start_time)) {
        errors.value.end_time = 'This field must be a date after start date';
    } else {
        delete errors.value.end_time;
    }

    // Validate description (trimming whitespace if it's a string)
    if (form.description && typeof form.description === 'string' && !form.description.trim()) {
        errors.value.description = 'This field cannot be only spaces';
    } else {
        delete errors.value.description;
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
