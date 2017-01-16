<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\DependencyInjection\Compiler;

use Lakion\SyliusElasticSearchBundle\DependencyInjection\Compiler\RegisterFilterTypePass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\DefinitionHasMethodCallConstraint;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class RegisterFilterTypePassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_collects_tagged_filter_types()
    {
        $this->setDefinition('lakion_sylius_elastic_search.form_registry.filters', new Definition());
        $this->setDefinition(
            'lakion_sylius_elastic_search.form_type.filter',
            (new Definition(\stdClass::class))->addTag('form.type')->addTag('filter.type', ['type' => 'option'])
        );

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'lakion_sylius_elastic_search.form_registry.filters',
            'add',
            ['default', 'option', \stdClass::class]
        );
    }

    /**
     * @test
     */
    public function it_does_nothing_if_there_is_no_filter_registry()
    {
        $this->compile();

        $this->assertContainerBuilderNotHasService('lakion_sylius_elastic_search.form_registry.filters');
    }

    /**
     * @test
     */
    public function it_does_nothing_if_there_is_no_tagged_filters()
    {
        $this->setDefinition('lakion_sylius_elastic_search.form_registry.filters', new Definition());
        $this->setDefinition(
            'lakion_sylius_elastic_search.form_type.filter',
            (new Definition())->addTag('tag', ['type' => 'option'])
        );

        $this->compile();

        $this->assertContainerBuilderNotHasServiceDefinitionWithMethodCall(
            'lakion_sylius_elastic_search.form_registry.filters',
            'add',
            ['default', 'option', 'class']
        );
    }

    /**
     * @test
     */
    public function tagged_filter_types_must_have_type_configured()
    {
        $this->setDefinition('lakion_sylius_elastic_search.form_registry.filters', new Definition());
        $this->setDefinition(
            'lakion_sylius_elastic_search.form_type.filter',
            (new Definition())->addTag('form.type')->addTag('filter.type')
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->compile();
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterFilterTypePass());
    }

    /**
     * @param string $serviceId
     * @param string $method
     * @param array $arguments
     */
    private function assertContainerBuilderNotHasServiceDefinitionWithMethodCall($serviceId, $method, $arguments)
    {
        $definition = $this->container->findDefinition($serviceId);

        self::assertThat(
            $definition,
            new \PHPUnit_Framework_Constraint_Not(new DefinitionHasMethodCallConstraint($method, $arguments))
        );
    }
}
