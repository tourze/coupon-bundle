<?php

namespace CouponBundle\Procedure\Code;

use CouponBundle\Entity\Channel;
use CouponBundle\Entity\Code;
use CouponBundle\Entity\Coupon;
use CouponBundle\Repository\CodeRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Tourze\DoctrineHelper\CacheHelper;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;

#[MethodTag('优惠券模块')]
#[MethodDoc('获取优惠券详情信息')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[MethodExpose('GetCouponCodeDetail')]
class GetCouponCodeDetail extends CacheableProcedure
{
    #[MethodParam('优惠券ID')]
    public string $codeId;

    public function __construct(
        private readonly CodeRepository $codeRepository,
        private readonly NormalizerInterface $normalizer,
        private readonly Security $security,
    ) {
    }

    public function execute(): array
    {
        $code = $this->codeRepository->findOneBy([
            'id' => $this->codeId,
            'owner' => $this->security->getUser(),
        ]);
        if (!$code) {
            throw new ApiException('找不到券码');
        }

        return $this->normalizer->normalize($code, 'array', ['groups' => 'restful_read']);
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        $key = static::buildParamCacheKey($request->getParams());
        if ($this->security->getUser()) {
            $key .= '-' . $this->security->getUser()->getUserIdentifier();
        }

        return $key;
    }

    protected function getCacheDuration(JsonRpcRequest $request): int
    {
        return MINUTE_IN_SECONDS;
    }

    protected function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield CacheHelper::getClassTags(Code::class, $request->getParams()->get('codeId'));
        yield CacheHelper::getClassTags(Channel::class, $request->getParams()->get('codeId'));
        yield CacheHelper::getClassTags(Coupon::class, $request->getParams()->get('codeId'));
    }
}
