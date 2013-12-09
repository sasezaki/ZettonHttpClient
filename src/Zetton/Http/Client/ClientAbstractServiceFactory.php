<?php
/**
 * Zetton (Zend Framework 2 Modules)
 *
 * @license   http://opensource.org/licenses/BSD-3-Clause
 */

namespace Zetton\Http\Client;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Client;

/**
 * Http Client abstract service factory.
 *
 * Allows configuring several HttpClient instances.
 */
class ClientAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Can we create an client by the requested name?
     *
     * @param  ServiceLocatorInterface $services
     * @param  string $name
     * @param  string $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
    {
        $config = $this->getConfig($services);
        if (empty($config)) {
            return false;
        }

        return (
            isset($config[$requestedName])
            && is_array($config[$requestedName])
        );
    }

    /**
     * Create a http client
     *
     * @param  ServiceLocatorInterface $services
     * @param  string $name
     * @param  string $requestedName
     * @return Client
     */
    public function createServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
    {
        $config = $this->getConfig($services);
        $options = (!empty($config[$requestedName])) ? $config[$requestedName]: null;
        return new Client(null, $options);
    }

    /**
     * Get HttpClient configuration, if any
     *
     * @param  ServiceLocatorInterface $services
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $services)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$services->has('Config')) {
            $this->config = array();
            return $this->config;
        }

        $config = $services->get('Config');
        if (!isset($config['http'])
            || !is_array($config['http'])
        ) {
            $this->config = array();
            return $this->config;
        }

        $config = $config['http'];
        if (!isset($config['clients'])
            || !is_array($config['clients'])
        ) {
            $this->config = array();
            return $this->config;
        }

        $this->config = $config['clients'];
        return $this->config;
    }
}
