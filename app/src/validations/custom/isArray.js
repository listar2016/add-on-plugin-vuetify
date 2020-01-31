import { req, withParams } from 'vuelidate/lib/validators/common'
export default withParams({ type: 'array' }, (value) => {
  if (!req(value)) {
    return true
  }
  return Array.isArray(value)
})
