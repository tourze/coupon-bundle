<?php

namespace CouponBundle\Tests\Repository;

use CouponBundle\Entity\Coupon;
use CouponBundle\Enum\CouponType;
use CouponBundle\Event\BeforeGetUserCouponListEvent;
use CouponBundle\Repository\CodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CodeRepositoryTest extends TestCase
{
    private CodeRepository $codeRepository;
    private ManagerRegistry $registry;
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    
    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->registry->method('getManagerForClass')->willReturn($this->entityManager);
        
        $this->codeRepository = new CodeRepository(
            $this->registry,
            $this->eventDispatcher
        );
    }
    
    public function testSyncList(): void
    {
        $user = $this->createMock(UserInterface::class);
        
        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($event) use ($user) {
                return $event instanceof BeforeGetUserCouponListEvent
                    && $event->getUser() === $user;
            }));
        
        $this->codeRepository->syncList($user);
    }
    
    public function testCreateUserCouponCodesQueryBuilderBasic(): void
    {
        $user = $this->createMock(UserInterface::class);
        
        // 创建一个模拟的QueryBuilder
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // 设置方法链式调用返回自身
        $queryBuilder->method('orderBy')->willReturn($queryBuilder);
        $queryBuilder->method('addOrderBy')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        
        // 设置createQueryBuilder返回我们的模拟对象
        $this->codeRepository = $this->getMockBuilder(CodeRepository::class)
            ->setConstructorArgs([$this->registry, $this->eventDispatcher])
            ->onlyMethods(['createQueryBuilder', 'syncList'])
            ->getMock();
        
        $this->codeRepository->method('createQueryBuilder')->willReturn($queryBuilder);
        
        // 验证syncList方法被调用
        $this->codeRepository->expects($this->once())
            ->method('syncList')
            ->with($user);
        
        // 测试基本调用
        $result = $this->codeRepository->createUserCouponCodesQueryBuilder($user);
        $this->assertSame($queryBuilder, $result);
    }
    
    public function testCreateUserCouponCodesQueryBuilderWithStatus(): void
    {
        $user = $this->createMock(UserInterface::class);
        
        // 创建一个模拟的QueryBuilder
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // 设置方法链式调用返回自身
        $queryBuilder->method('orderBy')->willReturn($queryBuilder);
        $queryBuilder->method('addOrderBy')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        
        // 设置createQueryBuilder返回我们的模拟对象
        $this->codeRepository = $this->getMockBuilder(CodeRepository::class)
            ->setConstructorArgs([$this->registry, $this->eventDispatcher])
            ->onlyMethods(['createQueryBuilder', 'syncList'])
            ->getMock();
        
        $this->codeRepository->method('createQueryBuilder')->willReturn($queryBuilder);
        
        // 验证syncList方法被调用
        $this->codeRepository->expects($this->once())
            ->method('syncList')
            ->with($user);
        
        // 测试带状态的调用
        $result = $this->codeRepository->createUserCouponCodesQueryBuilder($user, [], 1);
        $this->assertSame($queryBuilder, $result);
    }
    
    public function testCreateUserCouponCodesQueryBuilderWithCoupons(): void
    {
        $user = $this->createMock(UserInterface::class);
        $coupon = $this->createMock(Coupon::class);
        
        // 创建一个模拟的QueryBuilder
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // 设置方法链式调用返回自身
        $queryBuilder->method('orderBy')->willReturn($queryBuilder);
        $queryBuilder->method('addOrderBy')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        
        // 设置createQueryBuilder返回我们的模拟对象
        $this->codeRepository = $this->getMockBuilder(CodeRepository::class)
            ->setConstructorArgs([$this->registry, $this->eventDispatcher])
            ->onlyMethods(['createQueryBuilder', 'syncList'])
            ->getMock();
        
        $this->codeRepository->method('createQueryBuilder')->willReturn($queryBuilder);
        
        // 验证syncList方法被调用
        $this->codeRepository->expects($this->once())
            ->method('syncList')
            ->with($user);
        
        // 测试带优惠券的调用
        $result = $this->codeRepository->createUserCouponCodesQueryBuilder($user, [$coupon]);
        $this->assertSame($queryBuilder, $result);
    }
    
    public function testCreateValidCouponCodesQueryBuilder(): void
    {
        $user = $this->createMock(UserInterface::class);
        $type = CouponType::DISCOUNT;
        
        // 创建一个模拟的QueryBuilder
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // 设置方法链式调用返回自身
        $queryBuilder->method('orderBy')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('leftJoin')->willReturn($queryBuilder);
        
        // 设置createQueryBuilder返回我们的模拟对象
        $this->codeRepository = $this->getMockBuilder(CodeRepository::class)
            ->setConstructorArgs([$this->registry, $this->eventDispatcher])
            ->onlyMethods(['createQueryBuilder', 'syncList'])
            ->getMock();
        
        $this->codeRepository->method('createQueryBuilder')->willReturn($queryBuilder);
        
        // 验证syncList方法被调用
        $this->codeRepository->expects($this->once())
            ->method('syncList')
            ->with($user);
        
        // 验证orderBy方法调用
        $queryBuilder->expects($this->once())
            ->method('orderBy')
            ->with('a.gatherTime', 'DESC');
        
        // 验证andWhere方法调用
        $queryBuilder->expects($this->atLeastOnce())
            ->method('andWhere')
            ->with($this->logicalOr(
                $this->equalTo('a.useTime IS NULL'),
                $this->equalTo('c.type = :type')
            ));
        
        // 验证setParameter方法调用 - 使用单个调用验证
        $queryBuilder->expects($this->atLeastOnce())
            ->method('setParameter')
            ->with($this->logicalOr(
                $this->equalTo('user'),
                $this->equalTo('now'),
                $this->equalTo('type')
            ), $this->anything());
        
        // 验证leftJoin调用
        $queryBuilder->expects($this->once())
            ->method('leftJoin')
            ->with('a.coupon', 'c');
        
        $result = $this->codeRepository->createValidCouponCodesQueryBuilder($user, $type);
        $this->assertSame($queryBuilder, $result);
    }
    
    public function testCreateInvalidCouponCodesQueryBuilder(): void
    {
        $user = $this->createMock(UserInterface::class);
        $type = CouponType::DISCOUNT;
        
        // 创建一个模拟的QueryBuilder
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // 设置方法链式调用返回自身
        $queryBuilder->method('orderBy')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('leftJoin')->willReturn($queryBuilder);
        
        // 设置createQueryBuilder返回我们的模拟对象
        $this->codeRepository = $this->getMockBuilder(CodeRepository::class)
            ->setConstructorArgs([$this->registry, $this->eventDispatcher])
            ->onlyMethods(['createQueryBuilder', 'syncList'])
            ->getMock();
        
        $this->codeRepository->method('createQueryBuilder')->willReturn($queryBuilder);
        
        // 验证syncList方法被调用
        $this->codeRepository->expects($this->once())
            ->method('syncList')
            ->with($user);
        
        // 验证orderBy方法调用
        $queryBuilder->expects($this->once())
            ->method('orderBy')
            ->with('a.gatherTime', 'DESC');
        
        // 验证where方法调用
        $queryBuilder->expects($this->once())
            ->method('where')
            ->with('a.owner = :user and (a.valid = false or a.expireTime <= :now)');
        
        // 验证andWhere方法调用
        $queryBuilder->expects($this->once())
            ->method('andWhere')
            ->with('c.type = :type');
        
        // 验证leftJoin方法调用
        $queryBuilder->expects($this->once())
            ->method('leftJoin')
            ->with('a.coupon', 'c');
        
        $result = $this->codeRepository->createInvalidCouponCodesQueryBuilder($user, $type);
        $this->assertSame($queryBuilder, $result);
    }
} 