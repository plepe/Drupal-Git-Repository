<?
/**
 * Open up a connection to memcached (if possible)
 * @return either null or the memcache class
 */
function _git_repo_cache_connect() {
  global $_git_repo_memcache;

  // we don't now about a class called Memcache ... ignore
  if(!class_exists("Memcache"))
    return null;

  // If we need to open connection to memcache
  if(!$_git_repo_memcache) {
    $_git_repo_memcache = new Memcache;
    $_git_repo_memcache->connect('localhost');
  }

  return $_git_repo_memcache;
}

/**
 * Retrive the value of a key from cache
 * @param the key
 * @return NULL: memcache not available or no value exists OR the value
 */
function _git_repo_cache_get($key) {
  // connect to memcached
  if(!$_git_repo_memcache=_git_repo_cache_connect())
    return null;

  // check if value exists
  $value = $_git_repo_memcache->get($key);

  // no value there
  if($value === false)
    return null;

  // return original value
  return unserialize($value);
}

/**
 * Set a key to value for 10 secs
 * @param the key
 * @param the value
 * @return the value or null if memcache not available
 */
function _git_repo_cache_set($key, $value) {
  // connect to memcached
  if(!$_git_repo_memcache=_git_repo_cache_connect())
    return null;

  // save serialized value with 10 sec expiry time
  $_git_repo_memcache->set($key, serialize($value), null, 10);

  return $value;
}
