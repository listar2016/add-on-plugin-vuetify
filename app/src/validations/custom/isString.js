import { req } from 'vuelidate/lib/validators/common'

const mustBeStringVal = (value) => {
  return !req(value) || typeof value === 'string';
}

export default mustBeStringVal
