import { validationMessages } from '@/validations';

export default {
  created(){
    let errorMessages = {};
    if(this.$v && this.$v.$invalid){
      Object.keys(this.$v.$params).forEach((field) => {
        if(this.$v[field].$invalid){
          errorMessages[field] = [];
          Object.keys(this.$v[field].$params).forEach(rule => {
            if(!this.$v[field][rule]){
              let msg = validationMessages[rule] ? validationMessages[rule].replace('{attribute}', field): `The ${field} is not valid`;
              errorMessages[field].push({ field, rule, msg});
            }
          });
        }
      })
    }
    // show error message
    Object.keys(errorMessages).forEach(field => {
      errorMessages[field].forEach((error)=>{
        console.warn(error.msg);
      })
    });

  }
}
