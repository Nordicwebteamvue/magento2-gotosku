# Go To SKU for Magento 2

[![Build Status](https://travis-ci.org/kodbruket/magento2-gotosku.svg?branch=master)](https://travis-ci.org/kodbruket/magento2-gotosku)

This is a tiny Magento module that makes a 301 redirect to the product page when a SKU is passed to it's controller: `https://example.com/gotosku/?sku=example-sku`. If the product can't be found a 404 page will be shown instead.

## Installation

The easiest way to install the extension is to use [Composer](https://getcomposer.org), just run the following commands:

`$ composer require kodbruket/magento2-gotosku`

`$ bin/magento module:enable Kodbruket_GoToSku`

`$ bin/magento setup:upgrade`