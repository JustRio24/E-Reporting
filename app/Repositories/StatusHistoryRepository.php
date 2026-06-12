<?php

namespace App\Repositories;

use App\Models\StatusHistory;
use Illuminate\Database\Eloquent\Collection;

class StatusHistoryRepository extends BaseRepository
{
    public function __construct(StatusHistory $model)
    {
        parent::__construct($model);
    }

    public function getByReport(int $damageReportId): Collection
    {
        return $this->newQuery()
            ->where('damage_report_id', $damageReportId)
            ->with('changedBy')
            ->oldest()
            ->get();
    }
}
