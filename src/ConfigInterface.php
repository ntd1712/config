<?php

namespace Chaos\Support\Config;

/**
 * Interface ConfigInterface.
 */
interface ConfigInterface
{
    /**
     * Gets all of the configuration values.
     *
     * @return array
     */
    public function all();

    /**
     * Gets a configuration value using a simple or nested key with dot notation.
     *
     * @param string $key The key to get the value for.
     * @param mixed $default Default value to return if the key does not exist.
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets a configuration value.
     *
     * @param string $key The key to set the value for.
     * @param mixed $value The value to set.
     *
     * @return bool
     */
    public function set($key, $value);

    /**
     * Deletes a given configuration value.
     *
     * @param string $key The key to delete.
     *
     * @return bool
     */
    public function delete($key);

    /**
     * Wipes clean the entire configuration.
     *
     * @return bool
     */
    public function clear();

    /**
     * Gets multiple configuration values.
     *
     * @param iterable $keys A list of keys that can obtained in a single operation.
     * @param mixed $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key/value pairs.
     */
    public function getMultiple($keys, $default = null);

    /**
     * Sets multiple configuration values.
     *
     * @param iterable $values A list of key/value pairs.
     *
     * @return bool
     */
    public function setMultiple($values);

    /**
     * Deletes multiple configuration values.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool
     */
    public function deleteMultiple($keys);

    /**
     * Determines whether the given configuration value is present.
     *
     * @param string $key The key to check.
     *
     * @return bool
     */
    public function has($key);
}
