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
    public function isDeactivated(Model $model): bool
    {
        // Check if the model exists and has a `deleted_at` timestamp
        return $model && $model->deleted_at !== null;
    }
}
