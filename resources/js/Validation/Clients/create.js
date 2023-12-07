// ClientsValidation.js
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
    if (!form.name || !form.name.trim()) {
        errors.value.name = 'This field is required';
    }

    // Validate email (trimming whitespace)
    if (!form.email || !form.email.trim()) {
        errors.value.email = 'This field is required';
    } else if (!/^\S+@\S+\.\S+$/.test(form.email.trim())) {
        errors.value.email = 'This field must be a valid email';
    }

    // Validate phone_number (trimming whitespace)
    if (!form.phone_number || !form.phone_number.trim()) {
        errors.value.phone_number = 'This field is required';
    } // Additional validation can be added for phone_number if needed

    // Validate address (trimming whitespace if it's a string)
    if (form.address && typeof form.address === 'string' && !form.address.trim()) {
        errors.value.address = 'This field cannot be only spaces';
    } else if (form.address && typeof form.address !== 'string') {
        errors.value.address = 'This field must be a string';
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