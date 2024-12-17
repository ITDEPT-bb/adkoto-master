<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HandlesSoftDeletes
{
    /**
     * Determine if a model has been soft deleted.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return bool
     */
    public function isDeactivated($model)
    {
        if ($model instanceof \Illuminate\Database\Eloquent\Model) {
            return $model->deleted_at !== null;
        }

        return false;
    }
}
