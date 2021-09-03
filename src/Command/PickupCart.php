<?php

declare(strict_types=1);

namespace App\Command;

use Sylius\Bundle\ApiBundle\Command\Cart\PickupCart as BasePickupCart;

class PickupCart extends BasePickupCart
{
    public ?string $origin;
}
