<?php

namespace App\Http\Requests;

use App\Enums\WorkOrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $workOrder = $this->route('work_order');
        if (is_numeric($workOrder)) {
            $workOrder = \App\Models\WorkOrder::find($workOrder);
        }
        return $workOrder && $this->user()->can('update', $workOrder);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(WorkOrderStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
