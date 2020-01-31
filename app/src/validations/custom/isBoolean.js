import { req } from 'vuelidate/lib/validators/common'

const mustBeBoolean = (value) => {
  return !req(value) || typeof value === 'boolean';
}

export default mustBeBoolean
