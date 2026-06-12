<?php

namespace App\Repositories;

use App\Models\DamagePhoto;
use Illuminate\Database\Eloquent\Collection;

class DamagePhotoRepository extends BaseRepository
{
    public function __construct(DamagePhoto $model)
    {
        parent::__construct($model);
    }

    public function getByReport(int $damageReportId): Collection
    {
        return $this->newQuery()->where('damage_report_id', $damageReportId)->get();
    }

    public function deleteByReport(int $damageReportId): int
    {
        return $this->newQuery()->where('damage_report_id', $damageReportId)->delete();
    }
}
