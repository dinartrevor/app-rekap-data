<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'case_number',
        'clarification',
        'trial_date',
        'mediator',
        'notes',
        'description',
        'file_sk',
        'file_suit',
        'file_proof',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];


    /**
     * Get all of the comments for the LegalCase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plaintiff(): HasMany
    {
        return $this->hasMany(CasePlaintiff::class);
    }

    /**
     * Get all of the comments for the LegalCase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function defendant(): HasMany
    {
        return $this->hasMany(CaseDefendant::class);
    }

    public function getPlaintiffNamesHtml()
    {
        $html = "<ol>";

        foreach ($this->plaintiff as $index => $plaintiff) {
            $html .= "<li>" . e($plaintiff->name) . "</li>";
        }

        $html .= "</ol>";

        return $html;
    }

    public function getDefendantNamesHtml()
    {
        $html = "<ol>";

        foreach ($this->defendant as $index => $defendant) {
            $html .= "<li>" . e($defendant->name) . "</li>";
        }

        $html .= "</ol>";

        return $html;
    }
}
