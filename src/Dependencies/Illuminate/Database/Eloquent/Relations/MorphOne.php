<?php

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Relations;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Collection;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Model;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;

class MorphOne extends MorphOneOrMany
{
    use SupportsDefaultModels;

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        if (is_null($this->getParentKey())) {
            return $this->getDefaultFor($this->parent);
        }

        return $this->query->first() ?: $this->getDefaultFor($this->parent);
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array  $models
     * @param  string  $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array  $models
     * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Collection  $results
     * @param  string  $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        return $this->matchOne($models, $results, $relation);
    }

    /**
     * Make a new related instance for the given model.
     *
     * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Model  $parent
     * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Model
     */
    public function newRelatedInstanceFor(Model $parent)
    {
        return $this->related->newInstance()
                    ->setAttribute($this->getForeignKeyName(), $parent->{$this->localKey})
                    ->setAttribute($this->getMorphType(), $this->morphClass);
    }
}