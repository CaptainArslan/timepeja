<?php

namespace App\Traits;

trait HasOrganization
{
    public function scopeByOrganization($query, $organizationId)
    {
        return $query->when($organizationId, function ($query, $organizationId) {
            return $query->where('organization_id', $organizationId);
        });
    }
}
