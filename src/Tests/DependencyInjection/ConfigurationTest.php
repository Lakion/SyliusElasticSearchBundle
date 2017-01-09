<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\DependencyInjection;

use Lakion\SyliusElasticSearchBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function configuration_can_be_empty()
    {
        $this->assertConfigurationIsValid([]);
    }

    /**
     * @test
     */
    public function filter_sets_cannot_be_empty()
    {
        $this->assertConfigurationIsInvalid([[
            'filter_scope' => [],
        ]]);
    }

    /**
     * @test
     */
    public function filter_set_cannot_be_empty()
    {
        $this->assertConfigurationIsInvalid([[
            'filter_scope' => [
                'first_filter_set' => [],
                'second_filter_set' => [],
            ],
        ]]);
    }

    /**
     * @test
     */
    public function it_builds_filter_scope_configuration()
    {
        $this->assertProcessedConfigurationEquals(
            [[
                'filter_sets' => [
                    't_shirts' => [
                        'filters' => [
                            'color' => [
                                'type' => 'option',
                                'options' => [
                                    'code' => 'tshirt_color',
                                ]
                            ],
                            'size' => [
                                'type' => 'option',
                                'options' => [
                                    'code' => 'tshirt_size',
                                ]
                            ]
                        ]
                    ],
                    'smartphones' => [
                        'filters' => [
                            'brand' => [
                                'type' => 'taxon',
                                'options' => [
                                    'code' => 'smartphone_brand',
                                ]
                            ]
                        ]
                    ]
                ]
            ]],
            [
                'filter_sets' => [
                    't_shirts' => [
                        'filters' => [
                            'color' => [
                                'type' => 'option',
                                'options' => [
                                    'code' => 'tshirt_color',
                                ]
                            ],
                            'size' => [
                                'type' => 'option',
                                'options' => [
                                    'code' => 'tshirt_size',
                                ]
                            ]
                        ]
                    ],
                    'smartphones' => [
                        'filters' => [
                            'brand' => [
                                'type' => 'taxon',
                                'options' => [
                                    'code' => 'smartphone_brand',
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return new Configuration();
    }
}
