<?php

namespace CouponBundle\Procedure\Code;

use CouponBundle\Entity\Channel;
use CouponBundle\Entity\Code;
use CouponBundle\Repository\CodeRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\DoctrineHelper\CacheHelper;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;

#[MethodTag('优惠券模块')]
#[MethodDoc('获取code channel信息')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[MethodExpose('GetCouponChannelsByCode')]
class GetCouponChannelsByCode extends CacheableProcedure
{
    #[MethodParam('券码')]
    public string $code;

    public function __construct(
        private readonly CodeRepository $codeRepository,
        private readonly Security $security,
    ) {
    }

    public function execute(): array
    {
        $code = $this->codeRepository->findOneBy([
            'sn' => $this->code,
            'owner' => $this->security->getUser(),
        ]);
        if (!$code) {
            throw new ApiException('找不到券码');
        }
        $result = [
            'currentChannel' => $code->getChannel()?->retrievePlainArray(),
        ];
        $coupon = $code->getCoupon();
        $channels = $coupon->getChannels();
        foreach ($channels as $channel) {
            $result['channels'][] = $channel->retrievePlainArray();
        }

        return $result;
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
        yield CacheHelper::getClassTags(Code::class);
        yield CacheHelper::getClassTags(Channel::class);
    }
}
