<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Services;

use Elastica\Client;
use Elastica\Response;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ElasticSearchSuspender implements SuspenderInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function waitForLoadingNumberOfData($number, $timeout)
    {
        $start = microtime(true);
        $end = $start + $timeout;

        do {
            $response = $this->refreshResponse();
            $count = !isset($response->getData()['count']) ? 0 : $response->getData()['count'];

            sleep(1);
        } while ($count !== $number && microtime(true) < $end);
    }

    /**
     * @return Response
     */
    private function refreshResponse()
    {
        return $this->client->request('_count');
    }
}
