<?php

namespace CouponBundle\Procedure\Code;

use CouponBundle\Entity\Code;
use CouponBundle\Repository\CodeRepository;
use CouponBundle\Repository\CouponRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use Tourze\JsonRPCPaginatorBundle\Procedure\PaginatorTrait;

#[MethodTag('优惠券模块')]
#[MethodDoc('获取指定用户的优惠券码列表（分页）')]
#[MethodExpose('GetCouponCodesByBizUserId')]
class GetCouponCodesByBizUserId extends CacheableProcedure
{
    use PaginatorTrait;

    #[MethodParam('用户ID')]
    public string $userId = '';

    #[MethodParam('指定优惠券ID列表')]
    public array $couponIds = [];

    #[MethodParam('状态，1待使用、2已使用、3已过期')]
    public int $status = 0;

    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly CodeRepository $codeRepository,
        private readonly UserLoaderInterface $userLoader,
    ) {
    }

    public function execute(): array
    {
        $user = $this->userLoader->loadUserByIdentifier($this->userId);
        if (empty($user)) {
            throw new ApiException('暂无记录');
        }

        $coupons = [];
        if (!empty($this->couponIds)) {
            $coupons = $this->couponRepository->findBy([
                'id' => $this->couponIds,
            ]);
            if (empty($coupons)) {
                // 如果指定的优惠券ID不存在，返回空结果
                return $this->fetchList($this->codeRepository->createQueryBuilder('a')->where('a.id = 0'), $this->formatItem(...));
            }
        }

        $qb = $this->codeRepository->createUserCouponCodesQueryBuilder($user, $coupons, $this->status);

        return $this->fetchList($qb, $this->formatItem(...));
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        $key = static::buildParamCacheKey($request->getParams());
        if ($request->getParams()->get('userId')) {
            $key .= '-' . $request->getParams()->get('userId');
        }

        return $key;
    }

    public function getCacheDuration(JsonRpcRequest $request): int
    {
        return 60;
    }

    public function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield null;
    }

    private function formatItem(Code $item): array
    {
        return $item->retrieveApiArray();
    }
}
