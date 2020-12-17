<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver\Elasticsearch;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\QueryBuilder\ProductQueryBuilderInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductResolverInterface;
use Elastica\Query\BoolQuery;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class ProductResolver implements ProductResolverInterface
{
    /** @var ProductQueryBuilderInterface */
    private $productQueryBuilder;

    /** @var PaginatedFinderInterface */
    private $productFinder;

    public function __construct(ProductQueryBuilderInterface $productQueryBuilder, PaginatedFinderInterface $paginatedFinder)
    {
        $this->productQueryBuilder = $productQueryBuilder;
        $this->productFinder = $paginatedFinder;
    }

    public function findMatchingProducts(CatalogInterface $catalog)
    {
        $query = new BoolQuery();
        if ($catalog->getRules()->count()) {
            $query = $this->productQueryBuilder->findMatchingProductsQuery($catalog->getConnectingRules(), $catalog->getRules());
        }
        $products = $this->productFinder->findPaginated($query);

        return $products->getCurrentPageResults();
    }
}