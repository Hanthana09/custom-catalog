<?php
/**
 * @package    Ajlan
 * @author     Namal Dissanayake
 * @copyright  2019 Namal Dissanayake
 */

namespace Ajlan\CustomCatalog\Api;

/**
 * @api
 * @since 100.0.0
 */
interface ProductManagerInterface
{
    /**
     * Get products by VPN
     *
     * @param string $VPN
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getByVPN($VPN);

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return mixed
     */
    public function update(\Magento\Catalog\Api\Data\ProductInterface $product);
}
