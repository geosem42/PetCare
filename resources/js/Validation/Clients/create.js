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

    // Validate name
    if (!form.name) {
        errors.value.name = 'This field is required';
    }

    // Validate email
    if (!form.email) {
        errors.value.email = 'This field is required';
    } else if (!/^\S+@\S+\.\S+$/.test(form.email)) {
        errors.value.email = 'This field must be a valid email';
    }

    // Validate phone_number
    if (!form.phone_number) {
        errors.value.phone_number = 'This field is required';
    } // Additional validation can be added for phone_number if needed

    // Validate address
    if (form.address && typeof form.address !== 'string') {
        errors.value.address = 'This field must be a string';
    }

    // The notes field is optional, remove any specific validation if not required

    // Add additional validation rules as needed
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