<?php

namespace Chaos\Support\Config;

use ArrayAccess;
use Closure;
use M1\Vars\Vars;

/**
 * Class VarsConfigAdapter.
 *
 * <code>
 * $vars = new ConfigAdapter(
 *   new Vars(
 *     array_merge(
 *       glob(ROOT_PATH . '/modules/core/__ASTERISK__/config.yml', GLOB_NOSORT),
 *       glob(ROOT_PATH . '/modules/app/__ASTERISK__/config.yml', GLOB_NOSORT),
 *       [ROOT_PATH . '/modules/config.yml']
 *     ),
 *     [
 *       'cache' => 'production' === ENVIRONMENT,
 *       'cache_path' => ROOT_PATH . '/storage/framework/cache', #/vars
 *       'loaders' => ['yaml'],
 *       'merge_globals' => false,
 *       'replacements' => [
 *         'base_path' => ROOT_PATH
 *       ]
 *     ]
 *   )
 * );
 * $container['vars'] = $vars;
 * </code>
 */
class VarsConfigAdapter implements ConfigInterface, ArrayAccess
{
    /**
     * @var Vars
     */
    private $vars;

    /**
     * Constructor.
     *
     * @param Vars $vars The Vars instance.
     */
    public function __construct(Vars $vars)
    {
        $this->vars = $vars;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function all()
    {
        return $this->vars->getContent();
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The key to get the value for.
     * @param mixed $default Default value to return if the key does not exist.
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $array = $this->vars->getContent();

        if (is_null($key)) {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (false === strpos($key, '.')) {
            return isset($array[$key]) ? $array[$key] : ($default instanceof Closure ? $default() : $default);
        }

        foreach (explode('.', $key) as $segment) {
            if (
                (is_array($array) && array_key_exists($segment, $array))
                || ($array instanceof ArrayAccess && $array->offsetExists($segment))
            ) {
                $array = $array[$segment];
            } else {
                return $default instanceof Closure ? $default() : $default;
            }
        }

        return $array;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The key to set the value for.
     * @param mixed $value The value to set.
     *
     * @return bool
     */
    public function set($key, $value)
    {
        $this->vars->set($key, $value);

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The key to delete.
     *
     * @return bool
     */
    public function delete($key)
    {
        $this->vars->offsetUnset($key);

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function clear()
    {
        $this->vars->setContent([]);

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param iterable $keys A list of keys that can obtained in a single operation.
     * @param mixed $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key/value pairs.
     */
    public function getMultiple($keys, $default = null)
    {
        $array = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                list($key, $default) = [$default, null];
            }

            $array[$key] = $this->get($key, $default);
        }

        return $array;
    }

    /**
     * {@inheritDoc}
     *
     * @param iterable $values A list of key/value pairs.
     *
     * @return bool
     */
    public function setMultiple($values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The key to check.
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->vars->offsetExists($key);
    }

    // <editor-fold defaultstate="collapsed" desc="ArrayAccess methods">

    /**
     * {@inheritDoc}
     *
     * @param string $offset An offset to check for.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->vars->offsetExists($offset);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $offset The offset to retrieve.
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->vars->offsetGet($offset);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->vars->offsetSet($offset, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->vars->offsetUnset($offset);
    }

    // </editor-fold>
}
