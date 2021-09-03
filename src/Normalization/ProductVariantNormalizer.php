<?php

declare(strict_types=1);

namespace App\Normalization;

use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Webmozart\Assert\Assert;

final class ProductVariantNormalizer implements NormalizerAwareInterface, ContextAwareNormalizerInterface
{
    private const ALREADY_CALLED = 'app_product_variant_normalizer_already_called';
    private NormalizerInterface $normalizer;

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param ProductVariantInterface|mixed $object
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        Assert::isInstanceOf($object, ProductVariantInterface::class);
        Assert::keyNotExists($context, self::ALREADY_CALLED);

        $context[self::ALREADY_CALLED] = true;

        $normalizedProductVariant = $this->normalizer->normalize($object, $format, $context);
        $normalizedProductVariant['currentStock'] = $object->getOnHand() - $object->getOnHold();

        return $normalizedProductVariant;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof ProductVariantInterface;
    }

}
