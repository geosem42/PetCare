// validation.js
import { useForm } from 'vee-validate';
import * as yup from 'yup';

export const useMyForm = () => {
  const { meta, errors, defineField, handleSubmit } = useForm({
    validationSchema: yup.object({
      name: yup.string().required('Client Name is required'),
      email: yup.string().email('Email must be a valid email address').required('Email is required'),
      phone_number: yup.number().typeError('Phone Number must be a number').notRequired(),
      address: yup.string().notRequired(),
      notes: yup.string().notRequired(),
    }),
  });

  // Define the fields
  const [name, nameAttrs] = defineField('name');
  const [email, emailAttrs] = defineField('email');
  const [phone_number, phoneNumberAttrs] = defineField('phone_number');
  const [address, addressAttrs] = defineField('address');
  const [notes, notesAttrs] = defineField('notes');

  return { handleSubmit, meta, errors, name, nameAttrs, email, emailAttrs, phone_number, phoneNumberAttrs, address, addressAttrs, notes, notesAttrs };
};
