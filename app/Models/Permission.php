<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id', 'module', 'read', 'create', 'edit', 'delete',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Define the composite unique constraint
    protected function uniqueConstraint()
    {
        return $this->unique(['role_id', 'module']);
    }
}
