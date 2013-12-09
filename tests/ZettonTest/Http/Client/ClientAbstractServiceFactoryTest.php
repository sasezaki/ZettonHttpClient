<?php
/**
 * Zetton (Zend Framework 2 Modules)
 *
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace ZendTest\Http\Client;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

class ClientAbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceManager;

    /**
     * Set up service manager and HttpClient configuration.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->serviceManager = new ServiceManager(new ServiceManagerConfig(array(
            'abstract_factories' => array('Zetton\Http\Client\ClientAbstractServiceFactory'),
        )));

        $this->serviceManager->setService('Config', array(
            'http' => array(
                'clients' => array(
                    'Zend\Http\Client' => array(),
                    'Zend\Http\Client2' => array(
                        'useragent' => 'foo',
                    ),
                ),
            ),
        ));
    }

    /**
     * @return array
     */
    public function providerValidService()
    {
        return array(
            array('Zend\Http\Client'),
            array('Zend\Http\Client2'),
        );
    }

    /**
     * @return array
     */
    public function providerInvalidService()
    {
        return array(
            array('Zend\Http\Client\Unknown'),
        );
    }

    /**
     * @param string $service
     * @dataProvider providerValidService
     */
    public function testValidService($service)
    {
        $actual = $this->serviceManager->get($service);
        $this->assertInstanceOf('Zend\Http\Client', $actual);
    }

    /**
     * @param string $service
     * @dataProvider providerInvalidService
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidService($service)
    {
        $actual = $this->serviceManager->get($service);
    }
}
