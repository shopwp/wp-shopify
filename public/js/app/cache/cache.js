const setCache = (name, value) => {
  return localStorage.setItem(name, value);
}

const getCache = (name) => {
  return localStorage.getItem(name);
}

const deleteCache = (name = false) => {

  if (!name) {
    return localStorage.clear();
  }

  return localStorage.removeItem(name);

}

export {
  setCache,
  getCache,
  deleteCache
}
