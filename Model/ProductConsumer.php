<?php
/**
 * @package    Ajlan
 * @author     Namal Dissanayake
 * @copyright  2019 Namal Dissanayake
 */

declare(strict_types=1);

namespace Ajlan\CustomCatalog\Model;

/**
 * Class ProductConsumer
 * @package Ajlan\CustomCatalog\Model
 */
class ProductConsumer
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ProductConsumer constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     */
    public function process(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        try {
            $this->productRepository->save($product);
            $this->logger->info('consumer ran with product data: ' . $product->getName());
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            $this->logger->critical(
                'Something went wrong while product creation process: ' . $exception->getMessage()
            );
        }
    }
}
