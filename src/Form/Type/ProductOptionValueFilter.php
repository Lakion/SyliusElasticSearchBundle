<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Repository;
use Lakion\SyliusElasticSearchBundle\Form\DataTransformer\ProductOptionCodesToStringTransformer;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use ONGR\ElasticsearchDSL\Aggregation\FiltersAggregation;
use ONGR\ElasticsearchDSL\Search;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Product\Model\ProductOptionValue;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductOptionValueFilter extends AbstractType
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var QueryFactoryInterface
     */
    private $productHasOptionCodeQueryFactory;

    /**
     * @var RepositoryInterface
     */
    private $productOptionValueRepository;

    /**
     * @param RepositoryManagerInterface $repositoryManager
     * @param QueryFactoryInterface $productHasOptionCodeQueryFactory
     * @param RepositoryInterface $productOptionValueRepository
     */
    public function __construct(
        RepositoryManagerInterface $repositoryManager,
        QueryFactoryInterface $productHasOptionCodeQueryFactory,
        RepositoryInterface $productOptionValueRepository
    ) {
        $this->repositoryManager = $repositoryManager;
        $this->productHasOptionCodeQueryFactory = $productHasOptionCodeQueryFactory;
        $this->productOptionValueRepository = $productOptionValueRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'product_option_code',
            EntityType::class, [
                'class' => $options['class'],
                'choice_value' => function (ProductOptionValue $productOptionValue) {
                    return $productOptionValue->getCode();
                },
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $queryBuilder = $repository->createQueryBuilder('o');

                    return $queryBuilder
                        ->leftJoin('o.option', 'option')
                        ->andWhere('option.code = :optionCode')
                        ->setParameter('optionCode', $options['code']);
                },
                'choice_label' => function (ProductOptionValue $productOptionValue) {

                    /** @var Repository $repository */
                    $repository = $this->repositoryManager->getRepository(Product::class);
                    $query = $this->buildAggregation($productOptionValue->getCode())->toArray();
                    $result = $repository->createPaginatorAdapter($query);
                    $aggregation = $result->getAggregations();
                    $count = $aggregation['agg_'.$productOptionValue->getCode()]['buckets'][$productOptionValue->getCode()]['doc_count'];

                    return sprintf('%s (%s)', $productOptionValue->getValue(), $count);
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;

        $builder->addModelTransformer(new ProductOptionCodesToStringTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('class', ProductOptionValue::class)
            ->setRequired('code')
            ->setAllowedTypes('code', 'string')
            ->setAllowedTypes('class', 'string')
        ;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sylius_product_option_value_filter';
    }

    /**
     * @param string $code
     *
     * @return Search
     */
    private function buildAggregation($code)
    {
        $hasOptionValueAggregation = new FiltersAggregation($code);

        $hasOptionValueAggregation->addFilter(
            $this->productHasOptionCodeQueryFactory->create(['option_value_code' => $code]),
            $code
        );

        $aggregationSearch = new Search();
        $aggregationSearch->addAggregation($hasOptionValueAggregation);

        return $aggregationSearch;
    }
}
