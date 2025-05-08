<?php

namespace CouponBundle\Entity;

use AntdCpBundle\Builder\Field\BraftEditor;
use App\Kernel;
use CouponBundle\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
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
use Tourze\EasyAdmin\Attribute\Column\TreeView;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Field\ImagePickerField;
use Tourze\EasyAdmin\Attribute\Field\RichTextField;
use Tourze\EasyAdmin\Attribute\Field\SelectField;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Filter\Keyword;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use Tourze\EnumExtra\Itemable;

#[AsPermission(title: '优惠券分类')]
#[Deletable]
#[Editable]
#[Creatable]
#[TreeView(dataModel: Category::class, targetAttribute: 'parent')]
#[ORM\Table(name: 'coupon_category', options: ['comment' => '优惠券分类表'])]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category implements \Stringable, Itemable, AdminArrayInterface
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

    /**
     * order值大的排序靠前。有效的值范围是[0, 2^32].
     */
    #[IndexColumn]
    #[FormField]
    #[ListColumn(order: 95, sorter: true)]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['default' => '0', 'comment' => '次序值'])]
    private ?int $sortNumber = 0;

    public function getSortNumber(): ?int
    {
        return $this->sortNumber;
    }

    public function setSortNumber(?int $sortNumber): self
    {
        $this->sortNumber = $sortNumber;

        return $this;
    }

    public function retrieveSortableArray(): array
    {
        return [
            'sortNumber' => $this->getSortNumber(),
        ];
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

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
    private ?bool $valid = false;

    #[FormField]
    #[Keyword]
    #[ListColumn]
    #[Groups(['restful_read', 'api_tree'])]
    #[ORM\Column(type: Types::STRING, length: 60, options: ['comment' => '分类名'])]
    private string $title;

    #[FormField(title: '上级分类')]
    #[Ignore]
    #[ListColumn(title: '上级分类')]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'children')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Category $parent = null;

    #[ImagePickerField]
    #[PictureColumn]
    #[FormField]
    #[ListColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => 'LOGO地址'])]
    private ?string $logoUrl = null;

    /**
     * @BraftEditor
     */
    #[RichTextField]
    #[FormField]
    #[Groups(['restful_read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '简介'])]
    private ?string $description = null;

    /**
     * 下级分类列表.
     *
     * @var Collection<Category>
     */
    #[Groups(['api_tree'])]
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Category::class)]
    private Collection $children;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, options: ['comment' => '备注'])]
    private ?string $remark = null;

    #[Groups(['restful_read', 'api_tree'])]
    #[SelectField(targetEntity: 'product.tag.fetcher', mode: 'multiple')]
    #[FormField]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '显示标签'])]
    private ?array $showTags = [];

    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Coupon::class)]
    private Collection $coupons;

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
        $this->children = new ArrayCollection();
        $this->coupons = new ArrayCollection();
    }

    public function __toString(): string
    {
        if (!$this->getId()) {
            return '';
        }

        return "#{$this->getId()} {$this->getTitle()}";
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<Category>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function setLogoUrl(?string $logoUrl): self
    {
        $this->logoUrl = $logoUrl;

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

    public function toSelectItem(): array
    {
        return [
            'label' => "#{$this->getId()} {$this->getTitle()}",
            'text' => "#{$this->getId()} {$this->getTitle()}",
            'value' => $this->getId(),
        ];
    }

    public function getShowTags(): ?array
    {
        return $this->showTags;
    }

    public function setShowTags(?array $showTags): self
    {
        $this->showTags = $showTags;

        return $this;
    }

    /**
     * @deprecated 兼容旧的写法，直接在这里返回所有数据
     */
    public static function genOptions(): array
    {
        $repo = Kernel::container()->get(CategoryRepository::class);
        $entities = $repo->findBy(['valid' => true]);

        $result = [];
        foreach ($entities as $entity) {
            $tmp = [];
            $tmp['text'] = $entity->getTitle();
            $tmp['label'] = $entity->getTitle();
            $tmp['value'] = $entity->getId();
            $result[] = $tmp;
        }

        return $result;
    }

    public function getCategoryChildren(bool $is_only_enabled = false): array
    {
        $result = [
            'title' => $this->getTitle(),
            'value' => $this->getId(),
            'key' => $this->getId(),

            'id' => $this->getId(),
            'name' => $this->getTitle(),
        ];

        // 查下一层
        $categories = $this->getChildren();
        if ($categories->count() > 0) {
            $result['children'] = [];
            foreach ($categories as $category) {
                // 如果只查询启用的分类，当前这个分类没有启用的话它的子目录也就不需要再查了
                if ($is_only_enabled && !$category->isValid()) {
                    continue;
                }

                $result['children'][] = $category->getCategoryChildren();
            }
        }

        return $result;
    }

    /**
     * 获取可以用来搜索的分类ID
     */
    public function getSearchableId(): array
    {
        $result = [$this->getId()];
        foreach ($this->getChildren() as $child) {
            $result = array_merge($result, $child->getSearchableId());
        }

        return array_values(array_unique($result));
    }

    /**
     * @deprecated 兼容旧的写法，直接在这里返回所有数据
     */
    public static function genTreeData(): array
    {
        $repo = Kernel::container()->get(CategoryRepository::class);

        // 第一层
        $entities = $repo->findBy([
            'parent' => null,
            'valid' => true,
        ]);

        $treeData = [];
        foreach ($entities as $level1Model) {
            $treeData[] = $level1Model->getCategoryChildren(true);
        }

        return $treeData;
    }

    public function getNestTitle(): string
    {
        if ($this->getParent()) {
            return "{$this->getParent()->getTitle()}/{$this->getTitle()}";
        }

        return "{$this->getTitle()}";
    }

    public function getSimpleArray(): array
    {
        $result = [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'logoUrl' => $this->getLogoUrl(),
            'valid' => $this->isValid(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'children' => [],
        ];

        $criteria = Criteria::create()->orderBy(['sortNumber' => Criteria::DESC]);
        foreach ($this->getChildren()->matching($criteria) as $child) {
            /* @var static $child */
            $result['children'][] = $child->getSimpleArray();
        }

        return $result;
    }

    /**
     * @return Collection<int, Coupon>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupon $coupon): static
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons->add($coupon);
            $coupon->setCategory($this);
        }

        return $this;
    }

    public function removeCoupon(Coupon $coupon): static
    {
        if ($this->coupons->removeElement($coupon)) {
            // set the owning side to null (unless already changed)
            if ($coupon->getCategory() === $this) {
                $coupon->setCategory(null);
            }
        }

        return $this;
    }

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'title' => $this->getTitle(),
            'logoUrl' => $this->getLogoUrl(),
            'description' => $this->getDescription(),
            'showTags' => $this->getShowTags(),
            ...$this->retrieveSortableArray(),
            'valid' => $this->isValid(),
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndTime(),
        ];
    }

    public function retrieveReadArray(): array
    {
        return [
            'id' => $this->getId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'logoUrl' => $this->getLogoUrl(),
            'valid' => $this->isValid(),
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
}
