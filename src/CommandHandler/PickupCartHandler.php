<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\PickupCart;
use App\Entity\Order\Order;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PickupCartHandler implements MessageHandlerInterface
{
    private MessageHandlerInterface $decoratedPickupCartHandler;

    public function __construct(MessageHandlerInterface $decoratedPickupCartHandler)
    {
        $this->decoratedPickupCartHandler = $decoratedPickupCartHandler;
    }

    public function __invoke(PickupCart $pickupCart): Order
    {
        /** @var Order $cart */
        $cart = ($this->decoratedPickupCartHandler)($pickupCart);

        $cart->setOrigin($pickupCart->origin ?? 'WebSummerCamp');

        return $cart;
    }
}
