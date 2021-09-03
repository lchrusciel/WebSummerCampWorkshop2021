<?php

declare(strict_types=1);

namespace App\Command;

use Sylius\Bundle\ApiBundle\Command\ChannelCodeAwareInterface;
use Sylius\Bundle\ApiBundle\Command\IriToIdentifierConversionAwareInterface;
use Sylius\Bundle\ApiBundle\Command\ShopUserIdAwareInterface;

final class PurchaseRequest implements ShopUserIdAwareInterface, ChannelCodeAwareInterface, IriToIdentifierConversionAwareInterface
{
    public string $productVariant;
    public ?string $channelCode = null;
    public $shopUserId;

    public function __construct(string $productVariant)
    {
        $this->productVariant = $productVariant;
    }

    public function getShopUserId()
    {
        return $this->shopUserId;
    }

    public function setShopUserId($shopUserId): void
    {
        $this->shopUserId = $shopUserId;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }
}
