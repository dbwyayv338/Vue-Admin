<?php

namespace App\Http\Traits;

use App\Http\Exceptions\InvalidParent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

trait CategoryTree
{

    /**
     * @var \Closure
     */
    protected $queryCallback;

    /**
     * Get children of current node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, $this->getParentColumn());
    }

    /**
     * Get parent of current node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, $this->getParentColumn());
    }

    /**
     * GET all parents.
     *
     * @return \Illuminate\Support\Collection
     */
    public function parents()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    /**
     * @return string
     */
    public function getParentColumn()
    {
        if (property_exists($this, 'parentColumn')) {
            return $this->parentColumn;
        }

        return 'parent_id';
    }

    /**
     * @return mixed
     */
    public function getParentKey()
    {
        return $this->{$this->getParentColumn()};
    }

    /**
     * Set parent column.
     *
     * @param string $column
     */
    public function setParentColumn($column)
    {
        $this->parentColumn = $column;
    }

    /**
     * Get title column.
     *
     * @return string
     */
    public function getTitleColumn()
    {
        if (property_exists($this, 'titleColumn')) {
            return $this->titleColumn;
        }

        return 'name';
    }

    /**
     * Set title column.
     *
     * @param string $column
     */
    public function setTitleColumn($column)
    {
        $this->titleColumn = $column;
    }

    /**
     * Get order column name.
     *
     * @return string
     */
    public function getOrderColumn()
    {
        if (property_exists($this, 'orderColumn')) {
            return $this->orderColumn;
        }

        return 'weight';
    }

    /**
     * Set order column.
     *
     * @param string $column
     */
    public function setOrderColumn($column)
    {
        $this->orderColumn = $column;
    }

     /**
     * @return string
     */
    public function getCategoryTypeRelationColumn()
    {
        if (property_exists($this, 'categoryTypeRelationColumn')) {
            return $this->categoryTypeRelationColumn;
        }

        return 'category_type_id';
    }

    /**
     * Set relation column.
     *
     * @param string $column
     */
    public function setCategoryTypeRelationColumn($column)
    {
        $this->categoryTypeRelationColumn = $column;
    }

    /**
     * Set query callback to model.
     *
     * @param \Closure|null $query
     *
     * @return $this
     */
    public function withQuery(\Closure $query = null)
    {
        $this->queryCallback = $query;

        return $this;
    }

    /**
     * Format data to tree like array.
     *
     * @return array
     */
    public function toTree($id)
    {
        return $this->buildNestedArray($id);
    }

    /**
     * Build Nested array.
     *
     * @param array $nodes
     * @param int   $parentId
     *
     * @return array
     */
    protected function buildNestedArray($id, array $nodes = [], $parentId = 0)
    {
        $branch = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes($id);
        }

        foreach ($nodes as $node) {
            if ($node[$this->getParentColumn()] == $parentId) {
                $children = $this->buildNestedArray($id, $nodes, $node[$this->getKeyName()]);

                if ($children) {
                    $node['children'] = $children;
                }

                $branch[] = $node;
            }
        }

        return $branch;
    }

    /**
     * Get all elements.
     *
     * @return mixed
     */
    public function allNodes($id, $ignoreItemId = null)
    {
        $self = new static();

        if ($this->queryCallback instanceof \Closure) {
            $self = call_user_func($this->queryCallback, $self);
        }


        if($ignoreItemId) {
            return $self->where($this->getCategoryTypeRelationColumn(), $id)
                ->where(function ($query) use ($ignoreItemId) {
                    $query->where($this->getParentColumn(), '!=', $ignoreItemId)->orWhereNull($this->getParentColumn());
                })
                ->orderBy($this->getOrderColumn())->get()->toArray();
        }

        return $self->where($this->getCategoryTypeRelationColumn(), $id)->orderBy($this->getOrderColumn())->get()->toArray();
    }

    /**
     * Get options for Select field in form.
     *
     * @param \Closure|null $closure
     * @param string        $rootText
     *
     * @return array
     */
    public static function selectOptions($id, $ignoreItemId = null, \Closure $closure = null)
    {
        $options = (new static())->withQuery($closure)->buildSelectOptions($id, $ignoreItemId);

        return collect($options)->all();
    }

    /**
     * Build options of select field in form.
     *
     * @param array  $nodes
     * @param int    $parentId
     * @param string $prefix
     * @param string $space
     *
     * @return array
     */
    protected function buildSelectOptions($id, $ignoreItemId, array $nodes = [], $parentId = 0, $prefix = '', $space = '&nbsp;')
    {
        $prefix = $prefix ?: '┝'.$space;

        $options = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes($id, $ignoreItemId);
        }

        foreach ($nodes as $index => $node) {
            if ($node[$this->getParentColumn()] == $parentId) {
                $node[$this->getTitleColumn()] = $prefix.$space.$node[$this->getTitleColumn()];

                $childrenPrefix = str_replace('┝', str_repeat($space, 6), $prefix).'┝'.str_replace(['┝', $space], '', $prefix);

                $children = $this->buildSelectOptions($id, null, $nodes, $node[$this->getKeyName()], $childrenPrefix);

                $options[$node[$this->getKeyName()]] = $node[$this->getTitleColumn()];

                if ($children) {
                    $options += $children;
                }
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $parentColumn = $this->getParentColumn();
        $newParent = $this->$parentColumn ?? null;
        $this->where($this->getParentColumn(), $this->getKey())->update([$this->getParentColumn() => $newParent]);

        return parent::delete();
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Model $branch) {
            $parentColumn = $branch->getParentColumn();

            if (Request::filled($parentColumn) && Request::input($parentColumn) == $branch->getKey()) {
                throw InvalidParent::create();
            }

            return $branch;
        });
    }
}
