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

namespace Kodbruket\GoToSku\Test\Unit\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\Product\Url;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Unit test for index controller
 *
 *
 * @category Kodbruket
 * @package  Kodbruket_GoToSku
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://github.com/kodbruket/magento2-gotosku
 */
class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $redirectMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $redirectFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productUrlMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $noSuchEntityExceptionMock;

    /**
     * @var \Kodbruket\GoToSku\Controller\Index\Index
     */
    protected $controller;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class, ['getParam'], '', false);

        $this->redirectMock = $this->getMock(Redirect::class, ['setHttpResponseCode', 'setUrl'], [], '', false);

        $this->redirectFactoryMock = $this->getMock(RedirectFactory::class, ['create'], [], '', false);
        $this->redirectFactoryMock->method('create')->willReturn($this->redirectMock);

        $this->productUrlMock = $this->getMock(Url::class, ['getUrl'], [], '', false);
        $this->productMock = $this->getMock(Product::class, ['getUrlModel'], [], '', false);
        $this->productMock->method('getUrlModel')->willReturn($this->productUrlMock);

        $this->productRepositoryMock = $this->getMock(ProductRepository::class, ['get'], [], '', false);
        $this->productRepositoryMock->method('get')->with('example-sku')->willReturn($this->productMock);

        $this->contextMock = $this->getMock(Context::class, ['getRequest'], [], '', false);
        $this->contextMock->method('getRequest')->willReturn($this->requestMock);

        $this->noSuchEntityExceptionMock = $this->getMock(NoSuchEntityException::class, [], [], '', false);

        $this->controller = new \Kodbruket\GoToSku\Controller\Index\Index($this->contextMock, $this->redirectFactoryMock, $this->productRepositoryMock);
    }

    /**
     * Test returns result instance
     *
     * @return void
     */
    public function testReturnsResultInstance()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn('example-sku');

        $this->assertInstanceOf(ResultInterface::class, $this->controller->execute());
    }

    /**
     * Test execute without SKU
     *
     * @return void
     *
     * @expectedException Magento\Framework\Exception\NotFoundException
     */
    public function testExecuteWithoutSku()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn(false);

        $this->controller->execute();
    }

    /**
     * Test execute without SKU
     *
     * @return void
     *
     * @expectedException Magento\Framework\Exception\NotFoundException
     */
    public function testExecuteWithInvalidSku()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn('invalid-sku');

        $this->productRepositoryMock->expects($this->once())
            ->method('get')
            ->with('invalid-sku')
            ->will($this->throwException($this->noSuchEntityExceptionMock));

        $this->controller->execute();
    }

    /**
     * Test execute without SKU
     *
     * @return void
     */
    public function testExecuteWithValidSku()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn('example-sku');

        $this->productUrlMock->expects($this->once())
            ->method('getUrl');

        $this->productMock->expects($this->once())
            ->method('getUrlModel')
            ->willReturn($this->productUrlMock);

        $this->productRepositoryMock->expects($this->once())
            ->method('get')
            ->with('example-sku')
            ->willReturn($this->productMock);

        $this->assertInstanceOf(ResultInterface::class, $this->controller->execute());
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->requestMock = null;
        $this->redirectMock = null;
        $this->redirectFactoryMock = null;
        $this->productUrlMock = null;
        $this->productMock = null;
        $this->productRepositoryMock = null;
        $this->contextMock = null;
        $this->noSuchEntityExceptionMock = null;
        $this->controller = null;
    }
}
