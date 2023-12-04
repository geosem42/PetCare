import { useForm } from 'vee-validate';
import * as yup from 'yup';

export const createValidationForm = () => {
  const { meta, errors, defineField, handleSubmit, validate } = useForm({
    validationSchema: yup.object({
      name: yup.string().required('Pet Name is required.'),
      client_id: yup.number().typeError('Client ID must be a number.').required('Client Name is required.'),
      species_id: yup.number().typeError('Species ID must be a number.').required('Species is required.'),
      breed_id: yup.number().typeError('Breed ID must be a number.').notRequired(),
      age: yup.number().typeError('Age must be a number.').notRequired(),
      gender: yup.string().notRequired(),
      photo: yup.string().notRequired(),
    }),
  });

  // Define the fields
  const [name, nameAttrs] = defineField('name');
  const [client_id, clientAttrs] = defineField('client_id');
  const [species_id, speciesAttrs] = defineField('species_id');
  const [breed_id, breedAttrs] = defineField('breed_id');
  const [age, ageAttrs] = defineField('age');
  const [gender, genderAttrs] = defineField('gender');
  const [photo, photoAttrs] = defineField('photo');

  return { handleSubmit, meta, errors, name, nameAttrs, client_id, clientAttrs, species_id, speciesAttrs, breed_id, breedAttrs, age, ageAttrs, gender, genderAttrs, photo, photoAttrs, validate };
};
