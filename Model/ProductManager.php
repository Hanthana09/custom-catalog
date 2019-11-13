<?php
/**
 * @package    Ajlan
 * @author     Namal Dissanayake
 * @copyright  2019 Namal Dissanayake
 */

namespace Ajlan\CustomCatalog\Model;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class ProductManager
 * @package Ajlan\CustomCatalog\Model
 */
class ProductManager implements \Ajlan\CustomCatalog\Api\ProductManagerInterface
{
    /**
     * product creation topic name
     */
    const MQ_UPDATE_TOPIC = 'custom_catalog.productUpdater';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    private $productResourceModel;

    /**
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    private $messageQueuePublisher;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ProductManager constructor.
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\MessageQueue\PublisherInterface $messageQueuePublisher
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $productResourceModel,
        \Magento\Framework\MessageQueue\PublisherInterface $messageQueuePublisher,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productResourceModel = $productResourceModel;
        $this->messageQueuePublisher = $messageQueuePublisher;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getByVPN($VPN)
    {
        /**
         * @TODO: Currently we process each requests. We could improve performance by implementing result caching.
         */
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('ProductID');
        $productCollection->addAttributeToSelect('CopyWriteInfo');
        $productCollection->addAttributeToSelect('VPN');
        $productCollection->addAttributeToFilter('VPN', $VPN);
        return $productCollection->getItems();
    }

    /**
     * @inheritDoc
     */
    public function update(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $validationResult = $this->productResourceModel->validate($product);
        if (true !== $validationResult) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Invalid product data: %1', implode(',', $validationResult))
            );
        }
        $this->messageQueuePublisher->publish(self::MQ_UPDATE_TOPIC, $product);
        return true;
    }
}
