# CouponBundle

CouponBundle 是一个基于 Symfony 的优惠券系统集成包，提供了完整的优惠券管理功能，包括优惠券创建、发放、使用、核销等功能。

## 系统要求

- PHP >= 8.1
- Symfony >= 6.0
- MySQL >= 8.0
- Redis >= 6.0

## 依赖

### 必需的 Bundle
- JsonRpcServerBundle：提供 RPC 接口支持
- AppBundle：提供基础契约定义
- AntdCpBundle：提供后台管理界面

### 可选的 Bundle
- EventBundle：提供事件追踪支持
- NotificationBundle：提供通知支持

## 核心功能

### 1. 优惠券管理

```php
use CouponBundle\Entity\Coupon;
use CouponBundle\Service\CouponService;
use CouponBundle\Enum\CouponType;
use CouponBundle\Enum\CouponStatus;

class CouponController
{
    public function __construct(
        private readonly CouponService $couponService
    ) {}

    public function create(Coupon $coupon): void
    {
        // 创建优惠券
        $this->couponService->create($coupon);
    }

    public function update(Coupon $coupon): void
    {
        // 更新优惠券
        $this->couponService->update($coupon);
    }

    public function delete(int $id): void
    {
        // 删除优惠券
        $this->couponService->delete($id);
    }

    public function issue(int $id, int $userId): void
    {
        // 发放优惠券
        $this->couponService->issue($id, $userId);
    }

    public function use(int $id, int $orderId): void
    {
        // 使用优惠券
        $this->couponService->use($id, $orderId);
    }

    public function verify(int $id): void
    {
        // 核销优惠券
        $this->couponService->verify($id);
    }
}
```

### 2. 优惠券规则管理

```php
use CouponBundle\Entity\CouponRule;
use CouponBundle\Service\CouponRuleService;
use CouponBundle\Enum\RuleType;

class CouponRuleController
{
    public function __construct(
        private readonly CouponRuleService $couponRuleService
    ) {}

    public function create(CouponRule $rule): void
    {
        // 创建规则
        $this->couponRuleService->create($rule);
    }

    public function update(CouponRule $rule): void
    {
        // 更新规则
        $this->couponRuleService->update($rule);
    }

    public function delete(int $id): void
    {
        // 删除规则
        $this->couponRuleService->delete($id);
    }

    public function validate(int $ruleId, array $context): bool
    {
        // 验证规则
        return $this->couponRuleService->validate($ruleId, $context);
    }
}
```

### 3. 优惠券模板管理

```php
use CouponBundle\Entity\CouponTemplate;
use CouponBundle\Service\CouponTemplateService;

class CouponTemplateController
{
    public function __construct(
        private readonly CouponTemplateService $couponTemplateService
    ) {}

    public function create(CouponTemplate $template): void
    {
        // 创建模板
        $this->couponTemplateService->create($template);
    }

    public function update(CouponTemplate $template): void
    {
        // 更新模板
        $this->couponTemplateService->update($template);
    }

    public function delete(int $id): void
    {
        // 删除模板
        $this->couponTemplateService->delete($id);
    }

    public function generate(int $templateId, int $count): array
    {
        // 批量生成优惠券
        return $this->couponTemplateService->generate($templateId, $count);
    }
}
```

### 4. 优惠券统计分析

```php
use CouponBundle\Service\CouponAnalyticsService;
use CouponBundle\Dto\AnalyticsFilter;

class CouponAnalyticsController
{
    public function __construct(
        private readonly CouponAnalyticsService $analyticsService
    ) {}

    public function getIssuanceStats(AnalyticsFilter $filter): array
    {
        // 获取发放统计
        return $this->analyticsService->getIssuanceStats($filter);
    }

    public function getUsageStats(AnalyticsFilter $filter): array
    {
        // 获取使用统计
        return $this->analyticsService->getUsageStats($filter);
    }

    public function getEffectivenessStats(AnalyticsFilter $filter): array
    {
        // 获取效果统计
        return $this->analyticsService->getEffectivenessStats($filter);
    }
}
```

## 性能优化

1. 缓存策略
   - 优惠券信息缓存
   - 规则缓存
   - 模板缓存
   - 统计数据缓存

2. 数据库优化
   - 分表策略
   - 索引优化
   - 读写分离
   - 批量操作优化

3. 并发控制
   - 分布式锁
   - 库存控制
   - 事务管理
   - 并发限制

## 调试指南

### 1. 开发工具
```bash
# 检查优惠券状态
php bin/console coupon:debug:status <coupon-id>

# 验证优惠券规则
php bin/console coupon:debug:rule <rule-id>

# 生成测试优惠券
php bin/console coupon:generate:test
```

### 2. 调试模式
```php
use CouponBundle\Debug\CouponDebugger;

class CouponController
{
    public function __construct(
        private readonly CouponDebugger $debugger
    ) {}
    
    public function debug(): void
    {
        $this->debugger->dump([
            'coupon' => $this->getCoupon(),
            'context' => $this->getContext()
        ]);
    }
}
```

## 常见问题

1. 优惠券发放失败
   - 现象：优惠券无法发放或发放后不可见
   - 原因：库存不足或权限配置错误
   - 解决：检查库存和权限设置

2. 优惠券使用失败
   - 现象：优惠券无法使用或使用时报错
   - 原因：使用条件不满足或状态异常
   - 解决：检查使用条件和优惠券状态

3. 规则验证问题
   - 现象：规则验证结果异常
   - 原因：规则配置错误或上下文数据不完整
   - 解决：检查规则配置和上下文数据

## 版本历史

- v2.0.0
  - 升级到 PHP 8.1
  - 添加统计分析
  - 改进规则引擎

- v1.5.0
  - 添加模板管理
  - 优化发放流程
  - 增加调试工具

- v1.0.0
  - 初始版本
  - 基础优惠券管理
  - 简单规则系统