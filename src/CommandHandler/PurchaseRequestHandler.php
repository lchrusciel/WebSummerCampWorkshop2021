<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\PickupCart;
use App\Command\PurchaseRequest;
use App\Entity\Channel\Channel;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Entity\Product\ProductVariant;
use App\Entity\User\ShopUser;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class PurchaseRequestHandler implements MessageHandlerInterface
{
    private FactoryInterface $orderFactory;
    private RepositoryInterface $shopUserRepository;
    private ChannelRepositoryInterface $channelRepository;
    private ProductVariantRepositoryInterface $productVariantRepository;
    private OrderModifierInterface $orderModifier;
    private OrderItemQuantityModifierInterface $orderItemQuantityModifier;
    private CartItemFactoryInterface $cartItemFactory;
    private \SM\Factory\FactoryInterface $stateMachineFactory;

    public function __construct(
        FactoryInterface $orderFactory,
        RepositoryInterface $shopUserRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        OrderModifierInterface $orderModifier,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        CartItemFactoryInterface $cartItemFactory,
        \SM\Factory\FactoryInterface $stateMachineFactory
    ) {
        $this->orderFactory = $orderFactory;
        $this->shopUserRepository = $shopUserRepository;
        $this->channelRepository = $channelRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->orderModifier = $orderModifier;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->cartItemFactory = $cartItemFactory;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    public function __invoke(PurchaseRequest $purchaseRequest)
    {
        /** @var Order $order */
        $order = $this->orderFactory->createNew();
        /** @var ShopUser $shopUser */
        $shopUser = $this->shopUserRepository->find($purchaseRequest->getShopUserId());
        /** @var Channel $channel */
        $channel = $this->channelRepository->findOneByCode($purchaseRequest->getChannelCode());

        $order->setCustomer($shopUser->getCustomer());
        $order->setChannel($channel);
        $order->setCurrencyCode($channel->getBaseCurrency()->getCode());
        $order->setLocaleCode($channel->getDefaultLocale()->getCode());
        $order->setTokenValue('WebSummerCamp');

        /** @var ProductVariant|null $productVariant */
        $productVariant = $this->productVariantRepository->findOneBy(['code' => $purchaseRequest->productVariant]);

        Assert::notNull($productVariant);

        /** @var OrderItem $cartItem */
        $cartItem = $this->cartItemFactory->createNew();
        $cartItem->setVariant($productVariant);

        $this->orderItemQuantityModifier->modify($cartItem, 1);
        $this->orderModifier->addToOrder($order, $cartItem);

        $stateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);

        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_ADDRESS);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_PAYMENT);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE);

        return $order;
    }
}
