<?php

namespace CouponBundle\Entity;

use AntdCpBundle\Builder\Field\DynamicFieldSet;
use AntdCpBundle\Service\FormFieldBuilder;
use AppBundle\Model\CouponEntity;
use BenefitBundle\Model\BenefitResource;
use CouponBundle\Enum\CouponType;
use CouponBundle\Repository\CouponRepository;
use CouponBundle\Repository\WechatMiniProgramConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Attribute\SnowflakeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Action\Editable;
use Tourze\EasyAdmin\Attribute\Column\BoolColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Column\PictureColumn;
use Tourze\EasyAdmin\Attribute\Event\BeforeCreate;
use Tourze\EasyAdmin\Attribute\Event\BeforeEdit;
use Tourze\EasyAdmin\Attribute\Event\OnLinkage;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Field\ImagePickerField;
use Tourze\EasyAdmin\Attribute\Field\LinkageField;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Filter\Keyword;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use Tourze\EnumExtra\Itemable;
use Tourze\ResourceManageBundle\Model\ResourceIdentity;
use Yiisoft\Arrays\ArrayHelper;

#[AsPermission(title: '优惠券')]
#[Deletable]
#[Editable]
#[Creatable]
#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Table(name: 'coupon_main', options: ['comment' => '优惠券'])]
class Coupon implements \Stringable, Itemable, AdminArrayInterface, ApiArrayInterface, BenefitResource, ResourceIdentity, CouponEntity
{
    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[CreatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
    private ?bool $valid = false;

    #[Filterable]
    #[ListColumn]
    #[Groups(['restful_read'])]
    #[SnowflakeColumn]
    #[ORM\Column(type: Types::STRING, length: 100, unique: true, options: ['comment' => '唯一编码'])]
    private ?string $sn = null;

    #[ListColumn(title: '分类')]
    #[FormField(title: '分类')]
    #[ORM\ManyToOne(inversedBy: 'coupons')]
    private ?Category $category = null;

    /**
     * @var Collection<Code>
     */
    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: Code::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $codes;

    #[FormField(span: 12)]
    #[Keyword]
    #[Groups(['restful_read'])]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '名称'])]
    private ?string $name = null;

    #[FormField(span: 6)]
    #[Groups(['restful_read'])]
    #[ListColumn(sorter: true)]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '领取后过期天数'])]
    private ?int $expireDay = null;

    #[FormField(span: 6)]
    #[LinkageField]
    #[Groups(['restful_read'])]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, enumType: CouponType::class, options: ['comment' => '类型'])]
    private ?CouponType $type = null;

    #[ImagePickerField]
    #[PictureColumn]
    #[FormField(span: 5)]
    #[Groups(['restful_read'])]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => 'ICON图标', 'default' => 'https://cdn.mixpwr.com/aichonghui/pic/other/shops/a4.png'])]
    private ?string $iconImg = null;

    #[ImagePickerField]
    #[PictureColumn]
    #[FormField(span: 5)]
    #[Groups(['restful_read'])]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '列表背景', 'default' => 'https://cdn.mixpwr.com/aichonghui/pic/myCenter/couponBg4.png'])]
    private ?string $backImg = null;

    /**
     * @DynamicFieldSet()
     *
     * @var Collection<Requirement>
     */
    #[FormField(title: '领取条件')]
    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: Requirement::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $requirements;

    /**
     * TODO 有一个值得关注的问题，就是如果在优惠券发送过程中修改了这个使用条件，旧的优惠券怎么处理.
     *
     * @DynamicFieldSet()
     *
     * @var Collection<Satisfy>
     */
    #[Groups(['restful_read'])]
    #[FormField(title: '使用条件')]
    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: Satisfy::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $satisfies;

    /**
     * @DynamicFieldSet()
     *
     * @var Collection<Discount>
     */
    #[FormField(title: '优惠信息')]
    #[Groups(['restful_read'])]
    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: Discount::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $discounts;

    #[FormField]
    #[Keyword]
    #[Groups(['restful_read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '备注'])]
    private ?string $remark = null;

    #[Groups(['restful_read'])]
    #[ORM\OneToOne(mappedBy: 'coupon', targetEntity: WechatMiniProgramConfig::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    private ?WechatMiniProgramConfig $wechatMiniProgramConfig = null;

    #[Groups(['restful_read'])]
    #[ORM\OneToOne(mappedBy: 'coupon', targetEntity: H5Link::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    private ?H5Link $h5Link = null;

    #[Groups(['restful_read'])]
    #[ORM\OneToOne(mappedBy: 'coupon', targetEntity: CommandConfig::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    private ?CommandConfig $commandConfig = null;

    #[Groups(['restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '可用开始时间'])]
    private ?\DateTime $startDateTime = null;

    #[Groups(['restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '可用结束时间'])]
    private ?\DateTime $endDateTime = null;

    #[FormField(span: 6)]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否需要激活'])]
    private ?bool $needActive = null;

    #[FormField(span: 6)]
    #[ORM\Column(nullable: true, options: ['comment' => '激活后有效天数'])]
    private ?int $activeValidDay = null;

    /**
     * @var Collection<Attribute>
     */
    /**
     * @DynamicFieldSet()
     *
     * @var Collection<Requirement>
     */
    #[FormField(title: '属性')]
    #[Groups(['restful_read'])]
    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: Attribute::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', orphanRemoval: true, indexBy: 'name')]
    private Collection $attributes;

    #[FormField]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '使用说明'])]
    private ?string $useDesc = null;

    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: CouponChannel::class)]
    private Collection $couponChannels;

    #[FormField(title: '渠道')]
    #[ORM\JoinTable(name: 'coupon_main_channel_relations')]
    #[ORM\ManyToMany(targetEntity: Channel::class, inversedBy: 'coupons', fetch: 'EXTRA_LAZY')]
    private Collection $channels;

    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'coupon', targetEntity: Batch::class)]
    private Collection $batches;

    #[FormField]
    #[Groups(['restful_read', 'admin_curd'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '开始有效时间'])]
    private ?\DateTimeInterface $startTime = null;

    #[FormField]
    #[Groups(['restful_read', 'admin_curd'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '截止有效时间'])]
    private ?\DateTimeInterface $endTime = null;

    public function __construct()
    {
        $this->codes = new ArrayCollection();
        $this->satisfies = new ArrayCollection();
        $this->discounts = new ArrayCollection();
        $this->requirements = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->couponChannels = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->batches = new ArrayCollection();
    }

    public function __toString(): string
    {
        if (!$this->getId()) {
            return '';
        }

        return "[{$this->getType()?->getLabel()}] {$this->getName()}";
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    #[OnLinkage]
    public function onTypeChange(array $form, array $record, CouponRepository $couponRepository, FormFieldBuilder $formFieldBuilder): array
    {
        $type = ArrayHelper::getValue($form['type'], 'value');
        if (!$type) {
            return [];
        }

        if ($type === CouponType::WEAPP_LINK->value) {
            $config = $this->getWechatMiniProgramConfig();
            if (!$config) {
                $config = new WechatMiniProgramConfig();
            }

            $fields = $formFieldBuilder->createFromReflectionClass(new \ReflectionClass(WechatMiniProgramConfig::class), $config);
            foreach ($fields as $field) {
                switch ($field->getId()) {
                    case 'appId':
                        $field->setValue($config->getAppId());
                        break;
                    case 'envVersion':
                        $field->setValue($config->getEnvVersion());
                        break;
                    case 'path':
                        $field->setValue($config->getPath());
                        break;
                }
            }
            //            $fields[0]->setValue($config->getAppId());
            //            $fields[1]->setValue($config->getPath());
            //            $fields[2]->setValue($config->getEnvVersion());

            return $fields;
        }

        if ($type === CouponType::H5_LINK->value) {
            $config = $this->getH5Link();
            if (!$config) {
                $config = new H5Link();
            }

            $fields = $formFieldBuilder->createFromReflectionClass(new \ReflectionClass(H5Link::class), $config);
            $fields[0]->setValue($config->getUrl());

            return $fields;
        }

        if ($type === CouponType::COMMAND->value) {
            $config = $this->getCommandConfig();
            if (!$config) {
                $config = new CommandConfig();
            }

            $fields = $formFieldBuilder->createFromReflectionClass(new \ReflectionClass(CommandConfig::class), $config);
            $fields[0]->setValue($config->getCommand());

            return $fields;
        }

        return [];
    }

    #[BeforeCreate]
    #[BeforeEdit]
    public function beforeCurdSave(array $form, WechatMiniProgramConfigRepository $configRepository): void
    {
        if (CouponType::WEAPP_LINK === $this->getType()) {
            $config = $this->getWechatMiniProgramConfig();
            if (!$config) {
                $config = new WechatMiniProgramConfig();
            }

            $config->setAppId($form['appId']);
            $config->setPath($form['path']);
            $config->setEnvVersion($form['envVersion']);
            $this->setWechatMiniProgramConfig($config);
        } else {
            if ($this->getWechatMiniProgramConfig()) {
                $this->getWechatMiniProgramConfig()->setAppId('');
                $this->getWechatMiniProgramConfig()->setPath('');
                $this->getWechatMiniProgramConfig()->setEnvVersion('');
            }
        }
        if (CouponType::COMMAND === $this->getType()) {
            $commandConfig = $this->getCommandConfig();
            if (!$commandConfig) {
                $commandConfig = new CommandConfig();
            }

            $commandConfig->setCommand($form['command']);
            $this->setCommandConfig($commandConfig);
        } else {
            if ($this->getCommandConfig()) {
                $this->getCommandConfig()->setCommand('');
            }
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSn(): ?string
    {
        return $this->sn;
    }

    public function setSn(?string $sn): self
    {
        $this->sn = $sn;

        return $this;
    }

    /**
     * @return Collection<int, Code>
     */
    public function getCodes(): Collection
    {
        return $this->codes;
    }

    public function addCode(Code $code): self
    {
        if (!$this->codes->contains($code)) {
            $this->codes[] = $code;
            $code->setCoupon($this);
        }

        return $this;
    }

    public function removeCode(Code $code): self
    {
        if ($this->codes->removeElement($code)) {
            // set the owning side to null (unless already changed)
            if ($code->getCoupon() === $this) {
                $code->setCoupon(null);
            }
        }

        return $this;
    }

    public function getType(): ?CouponType
    {
        return $this->type;
    }

    public function setType(?CouponType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExpireDay(): ?int
    {
        return $this->expireDay;
    }

    public function setExpireDay(int $expireDay): self
    {
        $this->expireDay = $expireDay;

        return $this;
    }

    /**
     * @return Collection<int, Satisfy>
     */
    public function getSatisfies(): Collection
    {
        return $this->satisfies;
    }

    public function addSatisfy(Satisfy $satisfy): self
    {
        if (!$this->satisfies->contains($satisfy)) {
            $this->satisfies[] = $satisfy;
            $satisfy->setCoupon($this);
        }

        return $this;
    }

    public function removeSatisfy(Satisfy $satisfy): self
    {
        if ($this->satisfies->removeElement($satisfy)) {
            // set the owning side to null (unless already changed)
            if ($satisfy->getCoupon() === $this) {
                $satisfy->setCoupon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Discount>
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    public function addDiscount(Discount $discount): self
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts[] = $discount;
            $discount->setCoupon($this);
        }

        return $this;
    }

    public function removeDiscount(Discount $discount): self
    {
        if ($this->discounts->removeElement($discount)) {
            // set the owning side to null (unless already changed)
            if ($discount->getCoupon() === $this) {
                $discount->setCoupon(null);
            }
        }

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    #[ListColumn(title: '券码数量')]
    public function renderCodeCount(): int
    {
        return $this->getCodes()->count();
    }

    /**
     * @return Collection<int, Requirement>
     */
    public function getRequirements(): Collection
    {
        return $this->requirements;
    }

    public function addRequirement(Requirement $requirement): self
    {
        if (!$this->requirements->contains($requirement)) {
            $this->requirements[] = $requirement;
            $requirement->setCoupon($this);
        }

        return $this;
    }

    public function removeRequirement(Requirement $requirement): self
    {
        if ($this->requirements->removeElement($requirement)) {
            // set the owning side to null (unless already changed)
            if ($requirement->getCoupon() === $this) {
                $requirement->setCoupon(null);
            }
        }

        return $this;
    }

    public function getWechatMiniProgramConfig(): ?WechatMiniProgramConfig
    {
        return $this->wechatMiniProgramConfig;
    }

    public function setWechatMiniProgramConfig(WechatMiniProgramConfig $wechatMiniProgramConfig): self
    {
        // set the owning side of the relation if necessary
        if ($wechatMiniProgramConfig->getCoupon() !== $this) {
            $wechatMiniProgramConfig->setCoupon($this);
        }

        $this->wechatMiniProgramConfig = $wechatMiniProgramConfig;

        return $this;
    }

    public function getStartDateTime(): ?\DateTime
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(?\DateTime $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getEndDateTime(): ?\DateTime
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(?\DateTime $endDateTime): self
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    /**
     * @return Collection<int, Attribute>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute, ?string $key = null): self
    {
        if (!$this->attributes->contains($attribute)) {
            if (null !== $key) {
                $this->attributes[$key] = $attribute;
            } else {
                $this->attributes[] = $attribute;
            }

            $attribute->setCoupon($this);
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->removeElement($attribute)) {
            // set the owning side to null (unless already changed)
            if ($attribute->getCoupon() === $this) {
                $attribute->setCoupon(null);
            }
        }

        return $this;
    }

    public function getIconImg(): ?string
    {
        return $this->iconImg ?: ($_ENV['COUPON_DEFAULT_ICON_IMG'] ?? null);
    }

    public function setIconImg(?string $iconImg): self
    {
        $this->iconImg = $iconImg;

        return $this;
    }

    public function getBackImg(): ?string
    {
        return $this->backImg;
    }

    public function setBackImg(?string $backImg): self
    {
        $this->backImg = $backImg;

        return $this;
    }

    public function getH5Link(): ?H5Link
    {
        return $this->h5Link;
    }

    public function setH5Link(H5Link $h5Link): self
    {
        // set the owning side of the relation if necessary
        if ($h5Link->getCoupon() !== $this) {
            $h5Link->setCoupon($this);
        }

        $this->h5Link = $h5Link;

        return $this;
    }

    public function toSelectItem(): array
    {
        return [
            'label' => "{$this->getId()} {$this->getName()}",
            'text' => "{$this->getId()} {$this->getName()}",
            'value' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    public function getUseDesc(): ?string
    {
        return $this->useDesc;
    }

    public function setUseDesc(?string $useDesc): self
    {
        $this->useDesc = $useDesc;

        return $this;
    }

    public function isNeedActive(): ?bool
    {
        return $this->needActive;
    }

    public function setNeedActive(?bool $needActive): self
    {
        $this->needActive = $needActive;

        return $this;
    }

    public function getActiveValidDay(): ?int
    {
        return $this->activeValidDay;
    }

    public function setActiveValidDay(?int $activeValidDay): self
    {
        $this->activeValidDay = $activeValidDay;

        return $this;
    }

    public function getCommandConfig(): ?CommandConfig
    {
        return $this->commandConfig;
    }

    public function setCommandConfig(?CommandConfig $commandConfig): self
    {
        if ($commandConfig->getCoupon() !== $this) {
            $commandConfig->setCoupon($this);
        }

        $this->commandConfig = $commandConfig;

        return $this;
    }

    /**
     * @return Collection<int, CouponChannel>
     */
    public function getCouponChannels(): Collection
    {
        return $this->couponChannels;
    }

    public function addCouponChannel(CouponChannel $couponChannel): self
    {
        if (!$this->couponChannels->contains($couponChannel)) {
            $this->couponChannels->add($couponChannel);
            $couponChannel->setCoupon($this);
        }

        return $this;
    }

    public function removeCouponChannel(CouponChannel $couponChannel): self
    {
        if ($this->couponChannels->removeElement($couponChannel)) {
            // set the owning side to null (unless already changed)
            if ($couponChannel->getCoupon() === $this) {
                $couponChannel->setCoupon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Channel>
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): self
    {
        if (!$this->channels->contains($channel)) {
            $this->channels->add($channel);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): self
    {
        $this->channels->removeElement($channel);

        return $this;
    }

    /**
     * @return Collection<int, Batch>
     */
    public function getBatches(): Collection
    {
        return $this->batches;
    }

    public function addBatch(Batch $batch): self
    {
        if (!$this->batches->contains($batch)) {
            $this->batches->add($batch);
            $batch->setCoupon($this);
        }

        return $this;
    }

    public function removeBatch(Batch $batch): self
    {
        if ($this->batches->removeElement($batch)) {
            // set the owning side to null (unless already changed)
            if ($batch->getCoupon() === $this) {
                $batch->setCoupon(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'sn' => $this->getSn(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'type' => $this->getType(),
            'valid' => $this->isValid(),
            'name' => $this->getName(),
            'expireDay' => $this->getExpireDay(),
            'iconImg' => $this->getIconImg(),
            'backImg' => $this->getBackImg(),
            'satisfies' => $this->getSatisfies(),
            'discounts' => $this->getDiscounts(),
            'remark' => $this->getRemark(),
            'needActive' => $this->isNeedActive(),
            'activeValidDay' => $this->getActiveValidDay(),
            'useDesc' => $this->getUseDesc(),
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndTime(),
        ];
    }

    public function retrieveApiArray(): array
    {
        $requirements = [];
        foreach ($this->getRequirements() as $requirement) {
            $requirements[] = $requirement->retrieveApiArray();
        }

        $satisfies = [];
        foreach ($this->getSatisfies() as $satisfy) {
            $satisfies[] = $satisfy->retrieveApiArray();
        }

        $discounts = [];
        foreach ($this->getDiscounts() as $discount) {
            $discounts[] = $discount->retrieveApiArray();
        }

        $attributes = [];
        foreach ($this->getAttributes() as $attribute) {
            $attributes[] = $attribute->retrieveApiArray();
        }

        return [
            'id' => $this->getId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'startDateTime' => $this->getStartDateTime()?->format('Y-m-d H:i:s'),
            'endDateTime' => $this->getEndDateTime()?->format('Y-m-d H:i:s'),
            'sn' => $this->getSn(),
            'name' => $this->getName(),
            'type' => $this->getType()?->value,
            'expireDay' => $this->getExpireDay(),
            'iconImg' => $this->getIconImg(),
            'requirements' => $requirements,
            'satisfies' => $satisfies,
            'discounts' => $discounts,
            'remark' => $this->getRemark(),
            'wechatMiniProgramConfig' => $this->getWechatMiniProgramConfig()?->retrieveApiArray(),
            'h5Link' => $this->getH5Link()?->retrieveApiArray(),
            'commandConfig' => $this->getCommandConfig()?->retrieveApiArray(),
            'attributes' => $attributes,
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndTime(),
        ];
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getResourceId(): string
    {
        return $this->getId();
    }

    public function getResourceLabel(): string
    {
        return (string) $this->getName();
    }
}
