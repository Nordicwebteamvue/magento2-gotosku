<?php
/**
 * Go To SKU
 *
 * PHP version 5.6
 *
 * @category Kodbruket
 * @package  Kodbruket_GoToSku
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://github.com/kodbruket/magento2-gotosku
 */

namespace Kodbruket\GoToSku\Controller\Index;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Index controller
 *
 * @category Kodbruket
 * @package  Kodbruket_GoToSku
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://github.com/kodbruket/magento2-gotosku
 */
class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultRedirectFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context                $context               Context
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory Redirect result factory
     * @param \Magento\Catalog\Model\ProductRepository             $productRepository     Product repository
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        parent::__construct($context);

        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\Controller\Result\RedirectFactory
     *
     * @throws \Magento\Framework\Exception\NotFoundException if SKU param can't be found in request
     * @throws \Magento\Framework\Exception\NotFoundException if the product repository can't find the product
     */
    public function execute()
    {
        $sku = $this->getRequest()->getParam('sku', false);

        if ($sku === false) {
            throw new NotFoundException(__('Page not found'));
        } else {
            try {
                $product = $this->productRepository->get($sku);
            } catch (NoSuchEntityException $e) {
                throw new NotFoundException(__('Page not found'));
            }
        }

        $url = $product->getUrlModel()->getUrl($product);

        $result = $this->resultRedirectFactory->create();
        $result->setUrl($url);
        $result->setHttpResponseCode(301);

        return $result;
    }
}
