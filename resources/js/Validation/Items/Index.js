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

  // Validate item_name (trimming whitespace)
  if (!form.item_name || !form.item_name.trim()) {
      errors.value.item_name = 'This field is required';
  } else if (form.item_name.length > 255) {
      errors.value.item_name = 'This field cannot exceed 255 characters';
  }

  // Validate description (trimming whitespace if it's a string)
  if (form.description && typeof form.description === 'string' && !form.description.trim()) {
      errors.value.description = 'This field cannot be only spaces';
  } else if (form.description && typeof form.description !== 'string') {
      errors.value.description = 'This field must be a string';
  }

  // Validate quantity
  if (form.quantity === undefined || form.quantity === null || form.quantity === '') {
    errors.value.quantity = 'This field is required';
  } else if (typeof form.quantity !== 'number' || !Number.isInteger(form.quantity)) {
      errors.value.quantity = 'This field must be a number';
  } else if (form.quantity < 0) {
      errors.value.quantity = 'This field cannot be less than 0';
  }

  // Validate unit_price to accept decimals
  if (form.unit_price === undefined || form.unit_price === null || form.unit_price === '') {
    errors.value.unit_price = 'This field is required';
  } else {
    const parsedUnitPrice = parseFloat(form.unit_price);
    if (isNaN(parsedUnitPrice)) {
      errors.value.unit_price = 'This field must be a number';
    } else if (parsedUnitPrice < 0) {
      errors.value.unit_price = 'This field cannot be less than 0';
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