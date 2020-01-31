const mustBeObject = (val) => {
    return val !== null && typeof val === 'object'
}
export default mustBeObject