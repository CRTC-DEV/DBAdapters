<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Chute extends Model
{
    protected $table = 'Chute';
    protected $primaryKey = 'Id';

    // Disable automatic timestamps
    public $timestamps = false;

    // Dynamically set the fillable columns
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Ensure $columns is always an array
        $columns = Schema::hasTable($this->getTable())
            ? Schema::getColumnListing($this->getTable())
            : [];

        // Populate the fillable property dynamically
        $this->fillable = array_diff($columns, ['created_at', 'updated_at']);
    }

    /**
     * Get all chutes
     */
    public function getAllChutes()
    {
        return self::orderBy('Name', 'asc')->get();
    }

    /**
     * Insert new chute
     */
    public function insertChute($data)
    {
        return self::create($data);
    }

    /**
     * Update chute by Id
     */
    public function updateChute($data, $id)
    {
        return self::where('Id', $id)->update($data);
    }

    /**
     * Get chute by name
     */
    public function getChuteByName($name)
    {
        return self::where('Name', $name)->first();
    }
}
