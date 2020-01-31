import { req } from 'vuelidate/lib/validators/common'

const mustBeColor = (value) => {
  if (!req(value)) {
    return true
  }
  const regHex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/igm
  const regRGB = /^(rgba|rgb)\(\s?\d{1,3}\,\s?\d{1,3}\,\s?\d{1,3}(\,\s?(\d|\d\.\d+))?\s?\)$/igm
  return regHex.test(value) || regRGB.test(value)
}

export default mustBeColor
