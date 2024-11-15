<?php

namespace Enqueue\Bundle\Tests\Functional;

use Enqueue\Bundle\Tests\Functional\App\CustomAppKernel;
use Enqueue\Symfony\Client\LazyProducer;

/**
 * @group functional
 */
class LazyProducerTest extends WebTestCase
{
    protected function setUp(): void
    {
        // do not call parent::setUp.
        // parent::setUp();
    }

    public function testShouldAllowGetLazyProducerWithoutError()
    {
        $this->customSetUp([
            'default' => [
                'transport' => [
                    'dsn' => 'invalidDSN',
                ],
            ],
        ]);

        /** @var LazyProducer $producer */
        $producer = static::$container->get('test_enqueue.client.default.lazy_producer');
        $this->assertInstanceOf(LazyProducer::class, $producer);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The DSN is invalid.');
        $producer->sendEvent('foo', 'foo');
    }

    public static function getKernelClass(): string
    {
        include_once __DIR__.'/App/CustomAppKernel.php';

        return CustomAppKernel::class;
    }

    protected function customSetUp(array $enqueueConfig)
    {
        static::$class = null;

        static::$client = static::createClient(['enqueue_config' => $enqueueConfig]);
        static::$client->getKernel()->boot();
        static::$kernel = static::$client->getKernel();
        static::$container = static::$kernel->getContainer();
    }

    protected static function createKernel(array $options = []): CustomAppKernel
    {
        /** @var CustomAppKernel $kernel */
        $kernel = parent::createKernel($options);

        $kernel->setEnqueueConfig(isset($options['enqueue_config']) ? $options['enqueue_config'] : []);

        return $kernel;
    }
}
