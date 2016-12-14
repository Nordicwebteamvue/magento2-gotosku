<?php

namespace Kodbruket\GoToSku\Controller\Index;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultRedirectFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

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
