<?php

namespace CouponBundle\Procedure\Category;

use CouponBundle\Entity\Category;
use CouponBundle\Repository\CategoryRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPCLockBundle\Procedure\LockableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use Doctrine\ORM\EntityManagerInterface;

#[MethodTag('优惠券分类管理')]
#[MethodDoc('创建优惠券分类')]
#[MethodExpose('AdminCreateCouponCategory')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Log]
class AdminCreateCouponCategory extends LockableProcedure
{
    #[MethodParam('标题')]
    public string $title;

    #[MethodParam('上级分类ID')]
    public ?string $parentId = null;

    #[MethodParam('LOGO地址')]
    public ?string $logoUrl = null;

    #[MethodParam('描述')]
    public ?string $description = null;

    #[MethodParam('是否有效')]
    public ?bool $valid = null;

    #[MethodParam('排序编号')]
    public ?int $sortNumber = 0;

    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(): array
    {
        $parent = null;
        if (null !== $this->parentId) {
            $parent = $this->categoryRepository->find($this->parentId);
            if (!$parent) {
                throw new ApiException('找不到上级分类');
            }
        }

        $cate = new Category();
        $cate->setParent($parent);
        $cate->setTitle($this->title);
        $cate->setDescription($this->description);
        $cate->setLogoUrl($this->logoUrl);
        $cate->setValid($this->valid);
        $cate->setSortNumber($this->sortNumber);
        $this->entityManager->persist($cate);
        $this->entityManager->flush();

        return [
            'id' => $cate->getId(),
            '__message' => '创建成功',
        ];
    }
}
