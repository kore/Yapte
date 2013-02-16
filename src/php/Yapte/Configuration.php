<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

/**
 * Configurarion handler
 *
 * @version $Revision$
 */
class Configuration
{
    /**
     * Inheritance definition
     *
     * @var array
     */
    private $inheritance = array(
        'development'  =>  'testing',
        'testing'      =>  'staging',
        'staging'      =>  'production'
    );

    /**
     * Internal configuration storage
     *
     * @var array
     */
    private $configuration = array();

    /**
     * Constructs a new ini based configuration instance.
     *
     * @param string $iniFile
     * @param string $environment
     */
    public function __construct($iniFile, $environment = 'production')
    {
        if (!is_file($iniFile) &&
            is_file($iniFile . '.dist')) {
            $this->parseIniFile($iniFile . '.dist', $environment);
        } else {
            $this->parseIniFile($iniFile, $environment);
        }

        if (is_file($iniFile . '.local')) {
            $this->parseIniFile($iniFile . '.local', $environment);
        }
    }

    /**
     * Parse ini file
     *
     * @param string $iniFile
     * @param string $environment
     * @return void
     */
    public function parseIniFile($iniFile, $environment = 'production')
    {
        // Suppress errors about "invalid" comments
        $old = error_reporting(error_reporting() & ~E_DEPRECATED);
        $configuration = parse_ini_file($iniFile, true);
        error_reporting($old);

        if (false === isset($configuration[$environment])) {
            throw new \UnexpectedValueException("Unknown environment $environment.");
        }

        $this->configuration = $this->arrayfy(
            $this->applyInheritance(
                array_merge(
                    $this->configuration,
                    $configuration[$environment]
                ),
                $configuration,
                $environment
            )
        );
    }

    /**
     * Inherit configuration options from upper level environments
     *
     * @param array $configuration
     * @param string $environment
     * @return void
     */
    protected function applyInheritance(array $configuration, array $parents, $environment)
    {
        $parent = $environment;
        while (isset($this->inheritance[$parent])) {
            $parent = $this->inheritance[$parent];

            if (isset($parents[$parent])) {
                $configuration = array_merge(
                    $parents[$parent],
                    $configuration
                );
            }
        }

        return $configuration;
    }

    /**
     * Converts configuration keys containing dots into arrays
     *
     * @param array $configuration
     * @return array
     */
    protected function arrayfy(array $configuration)
    {
        foreach ($configuration as $key => $value) {
            if (strpos($key, '.') === false) {
                continue;
            }

            $path = array_filter(explode('.', $key));
            unset($configuration[$key]);
            $current = &$configuration;
            foreach ($path as $element) {
                if (!isset($current[$element])) {
                    $current[$element] = array();
                }

                $current = &$current[$element];
            }
            $current = $value;
        }

        return $configuration;
    }

    /**
     * Returns the configuration value for the given $key.
     *
     * @param string $key
     * @return string
     */
    public function __get($key)
    {
        if (isset($this->configuration[$key])) {
            return $this->configuration[$key];
        }

        throw new \OutOfBoundsException("No configuration option $key available.");
    }

    /**
     * Get all configuration values as an array
     *
     * @return array
     */
    public function getAsArray()
    {
        return $this->configuration;
    }
}
