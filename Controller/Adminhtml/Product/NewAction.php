<?php
/**
 * @package    Ajlan
 * @author     Namal Dissanayake
 * @copyright  2019 Namal Dissanayake
 */


namespace Ajlan\CustomCatalog\Controller\Adminhtml\Product;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Framework\App\ObjectManager;

/**
 * Class NewAction
 * @package Ajlan\CustomCatalog\Controller\Adminhtml\Product
 */
class NewAction extends \Ajlan\CustomCatalog\Controller\Adminhtml\Product implements HttpGetActionInterface
{
    /**
     * @var Initialization\StockDataFilter
     * @deprecated 101.0.0
     */
    protected $stockFilter;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * NewAction constructor.
     * @param Action\Context $context
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Initialization\StockDataFilter $stockFilter
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\StockDataFilter $stockFilter,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->stockFilter = $stockFilter;
        parent::__construct($context, $productBuilder);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * Create new product page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->getParam('set')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $product = $this->productBuilder->build($this->getRequest());
        $this->_eventManager->dispatch('catalog_product_new_action', ['product' => $product]);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        if ($this->getRequest()->getParam('popup')) {
            $resultPage->addHandle(['popup', 'catalog_product_' . $product->getTypeId()]);
        } else {
            $resultPage->addHandle(['catalog_product_' . $product->getTypeId()]);
            $resultPage->setActiveMenu('Ajlan_CustomCatalog::products');
            $resultPage->getConfig()->getTitle()->prepend(__('Products'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Product'));
        }

        return $resultPage;
    }
}
