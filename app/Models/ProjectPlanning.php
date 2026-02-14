<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlanning extends Model
{
    protected $fillable = [
        'project_number',
        'project_name',
        'project_description',
        'project_type',
        'priority',
        'status',
        'start_date',
        'end_date',
        'estimated_duration_days',
        'estimated_duration_weeks',
        'estimated_duration_months',
        'project_address',
        'project_city',
        'project_state',
        'project_zipcode',
        'onsite_contact_person',
        'engineers_required',
        'technicians_required',
        'laborers_required',
        'needs_cranes',
        'needs_power_tools',
        'needs_safety_equipment',
        'needs_measurement_tools',
        'materials_required',
        'estimated_budget',
        'labor_cost',
        'material_cost',
        'equipment_rental_cost',
        'other_expenses',
        'requested_by',
        'department',
        'approved_by',
        'approved_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_date' => 'date',
        'estimated_budget' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'equipment_rental_cost' => 'decimal:2',
        'other_expenses' => 'decimal:2',
        'needs_cranes' => 'boolean',
        'needs_power_tools' => 'boolean',
        'needs_safety_equipment' => 'boolean',
        'needs_measurement_tools' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            // Auto-generate project number
            if (!$project->project_number) {
                $project->project_number = 'PRJ-' . date('Y') . '-' . str_pad(ProjectPlanning::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            
            // Set default values for fields removed from form
            $project->onsite_contact_person = $project->onsite_contact_person ?? 'N/A';
            $project->estimated_duration_days = $project->estimated_duration_days ?? 0;
            $project->estimated_duration_weeks = $project->estimated_duration_weeks ?? 0;
            $project->estimated_duration_months = $project->estimated_duration_months ?? 0;
            $project->engineers_required = $project->engineers_required ?? 0;
            $project->technicians_required = $project->technicians_required ?? 0;
            $project->laborers_required = $project->laborers_required ?? 0;
            $project->needs_cranes = $project->needs_cranes ?? false;
            $project->needs_power_tools = $project->needs_power_tools ?? false;
            $project->needs_safety_equipment = $project->needs_safety_equipment ?? false;
            $project->needs_measurement_tools = $project->needs_measurement_tools ?? false;
            $project->materials_required = $project->materials_required ?? '';
            $project->estimated_budget = $project->estimated_budget ?? 0;
            $project->labor_cost = $project->labor_cost ?? 0;
            $project->material_cost = $project->material_cost ?? 0;
            $project->equipment_rental_cost = $project->equipment_rental_cost ?? 0;
            $project->other_expenses = $project->other_expenses ?? 0;
        });

        static::updating(function ($project) {
            // Auto-set approved date when project is approved
            if ($project->status === 'Approved' && !$project->approved_date) {
                $project->approved_date = now();
            }
        });
    }

    public function getTotalCostAttribute()
    {
        return $this->labor_cost + $this->material_cost + $this->equipment_rental_cost + $this->other_expenses;
    }

    public function getTotalPersonnelAttribute()
    {
        return $this->engineers_required + $this->technicians_required + $this->laborers_required;
    }

    public function getDurationTextAttribute()
    {
        $parts = [];
        if ($this->estimated_duration_months > 0) {
            $parts[] = $this->estimated_duration_months . ' month' . ($this->estimated_duration_months > 1 ? 's' : '');
        }
        if ($this->estimated_duration_weeks > 0) {
            $parts[] = $this->estimated_duration_weeks . ' week' . ($this->estimated_duration_weeks > 1 ? 's' : '');
        }
        if ($this->estimated_duration_days > 0) {
            $parts[] = $this->estimated_duration_days . ' day' . ($this->estimated_duration_days > 1 ? 's' : '');
        }
        return implode(', ', $parts) ?: 'Not specified';
    }
}
