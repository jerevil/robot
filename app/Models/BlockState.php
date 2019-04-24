<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property array $blocks
 * @property boolean $finished
 */
class BlockState extends Model
{
    /** @var array - The attributes that are mass assignable. */
    protected $fillable = [
        'blocks',
        'finished',
    ];

    /** @var array - The attributes that should be casted to native types. */
    protected $casts = [
        'blocks' => 'array',
        'finished' => 'boolean',
    ];
}
