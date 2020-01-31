const mustBeFunction = (func) => {
    return func && {}.toString.call(func) === '[object Function]';
}

export default mustBeFunction