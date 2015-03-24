<?php

namespace SS6\ShopBundle\Model\Product;

use SS6\ShopBundle\Model\Pricing\Vat\VatFacade;

class ProductDataFactory {

	/**
	 * @var \SS6\ShopBundle\Model\Pricing\Vat\VatFacade
	 */
	private $vatFacade;

	public function __construct(VatFacade $vatFacade) {
		$this->vatFacade = $vatFacade;
	}

	/**
	 * @return \SS6\ShopBundle\Model\Product\ProductData
	 */
	public function createDefault() {
		$productData = new ProductData();

		$productData->vat = $this->vatFacade->getDefaultVat();

		return $productData;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Product $product
	 * @param \SS6\ShopBundle\Model\Product\ProductDomain[] $productDomains
	 * @return \SS6\ShopBundle\Model\Product\ProductData
	 */
	public function createFromProduct(Product $product, array $productDomains) {
		$productData = $this->createDefault();

		$translations = $product->getTranslations();
		$names = [];
		$descriptions = [];
		foreach ($translations as $translation) {
			$names[$translation->getLocale()] = $translation->getName();
			$descriptions[$translation->getLocale()] = $translation->getDescription();
		}
		$productData->name = $names;
		$productData->description = $descriptions;

		$productData->catnum = $product->getCatnum();
		$productData->partno = $product->getPartno();
		$productData->ean = $product->getEan();
		$productData->price = $product->getPrice();
		$productData->vat = $product->getVat();
		$productData->sellingFrom = $product->getSellingFrom();
		$productData->sellingTo = $product->getSellingTo();
		$productData->flags = $product->getFlags()->toArray();
		$productData->usingStock = $product->isUsingStock();
		$productData->stockQuantity = $product->getStockQuantity();
		$productData->availability = $product->getAvailability();
		$productData->outOfStockAvailability = $product->getOutOfStockAvailability();

		$productData->hidden = $product->isHidden();
		$hiddenOnDomains = [];
		foreach ($productDomains as $productDomain) {
			if ($productDomain->isHidden()) {
				$hiddenOnDomains[] = $productDomain->getDomainId();
			}
		}
		$productData->hiddenOnDomains = $hiddenOnDomains;

		$productData->categories = $product->getCategories()->toArray();
		$productData->priceCalculationType = $product->getPriceCalculationType();
		$productData->accessories = $product->getAccessories();

		return $productData;
	}

}
