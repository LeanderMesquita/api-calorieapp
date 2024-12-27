<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'average_calories' => $this['average_calories'],
            'new_entries' => $this['new_entries'],
            'last_average' => $this['last_average']
        ];
    }
}
