<?php

namespace CouponBundle\Procedure\Category;

use CouponBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPCLockBundle\Procedure\LockableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;

#[MethodTag('优惠券分类管理')]
#[MethodDoc('编辑优惠券分类')]
#[MethodExpose('AdminEditCouponCategory')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Log]
class AdminDeleteCouponCategory extends LockableProcedure
{
    #[MethodParam('分类ID')]
    public string $id;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function execute(): array
    {
        $cate = $this->categoryRepository->find($this->id);
        if (!$cate) {
            throw new ApiException('找不到指定分类');
        }

        $this->entityManager->remove($cate);
        $this->entityManager->flush();

        return [
            'id' => $cate->getId(),
            '__message' => '删除成功',
        ];
    }
}
