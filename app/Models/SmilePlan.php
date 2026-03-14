<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmilePlan extends Model
{
    protected $table = 'smile_plans';

    protected $fillable = [
        'plan_id',
        'name',
        'price',
    ];

    /**
     * Calculate final price for a specific user role.
     */
    public function calculatePriceForRole($role)
    {
        $service = Service::where('name', 'Smile Data')->first();
        if (!$service) return (float)$this->price;

        $networkMap = [
            'SMILE' => 'SMI01',
        ];

        $fieldCode = $networkMap[strtoupper($this->name)] ?? 'SMI01';
        $field = $service->fields()->where('field_code', $fieldCode)->first();
        
        $fee = $field ? (float)$field->base_price : 0;
        $markup = 0;

        if ($field) {
            $markup = (float)$field->prices()
                ->where('user_type', $role)
                ->value('price') ?? 0;
        }

        return (float)$this->price + $fee + $markup;
    }
}
