import config from '../config';

const has = (argvs, type) => {

  if (argvs.argv[type]) {
    return true;
  }

  return false;

}

const get = (argvs, prop) => {
  return argvs.argv[prop];
}

const isPro = (argvs) => {
  return argvs.argv.tier === 'pro'
}

const isFree = (argvs) => {
  return argvs.argv.tier === 'free';
}

const isBuilding = (argvs) => {
  return has(argvs, 'tier');
}

const getTier = (argvs) => {
  return get(argvs, 'tier');
}

const getRelease = (argvs) => {
  return get(argvs, 'release');
}

const hasRelease = (argvs) => {
  return has(argvs, 'release');
}

const hasCurrent = (argvs) => {
  return has(argvs, 'current');
}

const getCurrent = (argvs) => {
  return get(argvs, 'current');
}


export {
  isPro,
  isFree,
  isBuilding,
  getTier,
  getRelease,
  hasRelease,
  hasCurrent,
  getCurrent
}
