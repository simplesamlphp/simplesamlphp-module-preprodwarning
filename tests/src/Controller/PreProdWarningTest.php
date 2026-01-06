<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Module\preprodwarning\Controller;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Auth\ProcessingChain;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error;
use SimpleSAML\HTTP\RunnableResponse;
use SimpleSAML\Logger;
use SimpleSAML\Module\preprodwarning\Controller;
use SimpleSAML\Session;
use SimpleSAML\XHTML\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Set of tests for the controllers in the "preprodwarning" module.
 *
 * @package SimpleSAML\Test
 */
class PreProdWarningTest extends TestCase
{
    /** @var \SimpleSAML\Configuration */
    protected Configuration $config;

    /** @var \SimpleSAML\Logger */
    protected Logger $logger;

    /** @var \SimpleSAML\Session */
    protected Session $session;


    /**
     * Set up for each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->config = Configuration::loadFromArray(
            [
                'module.enable' => ['preprodwarning' => true],
            ],
            '[ARRAY]',
            'simplesaml',
        );

        $this->session = Session::getSessionFromRequest();

        Configuration::setPreLoadedConfig($this->config, 'config.php');

        $this->logger = new class () extends Logger {
            public static function info(string $string): void
            {
                // do nothing
            }
        };
    }


    /**
     * Test that a valid requests results in a Twig template
     */
    public function testMissingStateIdThrowsException(): void
    {
        $request = Request::create(
            '/warning',
            'GET',
        );

        $c = new Controller\PreProdWarning($this->config, $this->session);
        $c->setLogger($this->logger);

        $this->expectException(Error\BadRequest::class);
        $this->expectExceptionMessage('Missing required StateId query parameter.');

        $c->main($request);
    }


    /**
     * Test that a valid requests results in a Twig template
     */
    public function testWarning(): void
    {
        $request = Request::create(
            '/warning',
            'GET',
            ['StateId' => 'someStateId'],
        );

        $c = new Controller\PreProdWarning($this->config, $this->session);
        $c->setLogger($this->logger);
        $c->setAuthState(new class () extends State {
            public static function loadState(string $id, string $stage, bool $allowMissing = false): array
            {
                return [];
            }
        });

        $response = $c->main($request);

        // Validate response
        $this->assertInstanceOf(Template::class, $response);
        $this->assertTrue($response->isSuccessful());
    }


    /**
     */
    public function testWarningAccepted(): void
    {
        $request = Request::create(
            '/warning',
            'GET',
            ['StateId' => 'someStateId', 'yes' => 'yes'],
        );

        $c = new Controller\PreProdWarning($this->config, $this->session);
        $c->setLogger($this->logger);
        $c->setAuthState(new class () extends State {
            public static function loadState(string $id, string $stage, bool $allowMissing = false): array
            {
                return [
                    ProcessingChain::FILTERS_INDEX => [],
                    'ReturnURL' => 'https://example.org',
                ];
            }
        });
        $c->setProcessingChain(new class () extends ProcessingChain {
            public function __construct()
            {
                // stub
            }


            public static function resumeProcessing(array $state): void
            {
            }
        });

        $response = $c->main($request);

        // Validate response
        $this->assertInstanceOf(RunnableResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
    }
}
