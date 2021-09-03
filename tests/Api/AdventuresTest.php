<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiTestCase\JsonApiTestCase;
use Sylius\Bundle\ApiBundle\Command\Cart\AddItemToCart;
use Sylius\Bundle\ApiBundle\Command\Cart\PickupCart;
use Sylius\Component\Core\Test\Services\EmailChecker;
use Sylius\Tests\Api\Utils\ShopUserLoginTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class AdventuresTest extends JsonApiTestCase
{
    use ShopUserLoginTrait;

    public const CONTENT_TYPE_HEADER = ['CONTENT_TYPE' => 'application/ld+json', 'HTTP_ACCEPT' => 'application/ld+json'];

    /** @test */
    public function it_exposes_stock_size_of_the_product_variant(): void
    {
        $this->loadFixturesFromFile('test_data.yaml');

        $this->client->request('GET', '/api/v2/shop/product-variants/MUG_BLUE', [], [], self::CONTENT_TYPE_HEADER);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'adventure_1', Response::HTTP_OK);
    }

//    /** @test */
//    public function it_hides_translations_of_the_product_and_exposes_them_directly(): void
//    {
//        $this->loadFixturesFromFile('test_data.yaml');
//
//        $this->client->request('GET', '/api/v2/shop/products/MUG', [], [], self::CONTENT_TYPE_HEADER);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_2', Response::HTTP_OK);
//    }
//
//    /** @test */
//    public function it_calculates_stock_size_in_backend(): void
//    {
//        $this->loadFixturesFromFile('test_data.yaml');
//
//        $this->client->request('GET', '/api/v2/shop/product-variants/MUG_BLUE', [], [], self::CONTENT_TYPE_HEADER);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_3', Response::HTTP_OK);
//    }
//
//    /** @test */
//    public function it_exposes_a_supplier_resource_with_sylius_and_api_platform(): void
//    {
//        $this->loadFixturesFromFiles(['test_data.yaml', 'supplier.yaml']);
//
//        $this->client->request('GET', '/api/v2/shop/suppliers', [], [], self::CONTENT_TYPE_HEADER);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_4_1', Response::HTTP_OK);
//
//        $this->client->request('GET', '/api/v2/shop/suppliers/web-summer-camp', [], [], self::CONTENT_TYPE_HEADER);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_4_2', Response::HTTP_OK);
//    }

//    /** @test */
//    public function it_exposes_shipping_address_on_order_update(): void
//    {
//        $this->loadFixturesFromFiles(['test_data.yaml']);
//
//        $tokenValue = 'nAWw2jewpA';
//
//        /** @var MessageBusInterface $commandBus */
//        $commandBus = $this->get('sylius.command_bus');
//
//        $pickupCartCommand = new PickupCart($tokenValue, 'en_US');
//        $pickupCartCommand->setChannelCode('WEB');
//        $commandBus->dispatch($pickupCartCommand);
//
//        $addItemToCartCommand = new AddItemToCart('MUG_BLUE', 3);
//        $addItemToCartCommand->setOrderTokenValue($tokenValue);
//        $commandBus->dispatch($addItemToCartCommand);
//
//        $data = json_encode([
//            'shippingAddress' => [
//                'firstName'=> 'TEST',
//                'lastName'=> 'TEST',
//                'phoneNumber'=> '666111333',
//                'company'=> 'Potato Corp.',
//                'countryCode'=> 'US',
//                'provinceCode'=> null,
//                'provinceName'=> null,
//                'street'=> 'Top secret',
//                'city'=> 'Nebraska',
//                'postcode'=> '12343',
//            ]
//        ]);
//
//        $this->client->request('PUT', '/api/v2/shop/orders/' . $tokenValue, [], [], self::CONTENT_TYPE_HEADER, $data);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_5', Response::HTTP_OK);
//    }

//    /** @test */
//    public function it_removes_of_get_taxon_collection_endpoint(): void
//    {
//        $this->client->request('GET', '/api/v2/shop/taxons', [], [], self::CONTENT_TYPE_HEADER);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_6', Response::HTTP_NOT_FOUND);
//    }
//
//    /** @test */
//    public function it_automatically_assigns_origin_info_to_order(): void
//    {
//        $this->loadFixturesFromFiles(['test_data.yaml']);
//
//        $this->client->request('POST', '/api/v2/shop/orders', [], [], self::CONTENT_TYPE_HEADER, '{}');
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_7', Response::HTTP_CREATED);
//    }
//
//    /** @test */
//    public function it_allows_for_order_origin_code_to_be_send_during_pickup_cart(): void
//    {
//        $this->loadFixturesFromFiles(['test_data.yaml']);
//
//        $this->client->request('POST', '/api/v2/shop/orders', [], [], self::CONTENT_TYPE_HEADER, '{"origin": "Sibenik"}');
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_8', Response::HTTP_CREATED);
//    }
//
//    /** @test */
//    public function it_instantly_purchases_product(): void
//    {
//        $this->loadFixturesFromFiles(['test_data.yaml']);
//
//        $requestBody = json_encode([
//            'productVariant' => '/api/v2/shop/product-variants/MUG_BLUE',
//        ]);
//
//        $this->client->request('POST', '/api/v2/shop/purchase-request', [], [], self::CONTENT_TYPE_HEADER, $requestBody);
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_9_1', Response::HTTP_UNAUTHORIZED);
//
//        $token = $this->logInShopUser('oliver@doe.com');
//
//        $this->client->request(
//            'POST',
//            '/api/v2/shop/purchase-request',
//            [],
//            [],
//            array_merge(self::CONTENT_TYPE_HEADER, ['HTTP_Authorization' => sprintf('Bearer %s', $token)]),
//            $requestBody
//        );
//        $response = $this->client->getResponse();
//
//        $this->assertResponse($response, 'adventure_9_2', Response::HTTP_CREATED);
//    }
//
//    /** @test */
//    public function it_instantly_purchases_product_with_mailing_support(): void
//    {
//        $this->loadFixturesFromFiles(['test_data.yaml']);
//
//        /** @var Filesystem $filesystem */
//        $filesystem = $this->get('filesystem');
//
//        /** @var EmailChecker $emailChecker */
//        $emailChecker = $this->get('sylius.behat.email_checker');
//
//        $filesystem->remove($emailChecker->getSpoolDirectory());
//
//        $requestBody = json_encode([
//            'productVariant' => '/api/v2/shop/product-variants/MUG_BLUE',
//        ]);
//
//        $token = $this->logInShopUser('oliver@doe.com');
//
//        $this->client->request(
//            'POST',
//            '/api/v2/shop/purchase-request',
//            [],
//            [],
//            array_merge(self::CONTENT_TYPE_HEADER, ['HTTP_Authorization' => sprintf('Bearer %s', $token)]),
//            $requestBody
//        );
//
//        self::assertSame(1, $emailChecker->countMessagesTo('oliver@doe.com'));
//    }
}
