<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Gate extends Model
{
    protected $table = 'Gate';
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
     * Get all gates
     */
    public function getAllGates()
    {
        return self::orderBy('Name', 'asc')->get();
    }

    /**
     * Insert new gate
     */
    public function insertGate($data)
    {
        return self::create($data);
    }

    /**
     * Update gate by Id
     */
    public function updateGate($data, $id)
    {
        return self::where('Id', $id)->update($data);
    }

    /**
     * Get gate by name
     */
    public function getGateByName($name)
    {
        return self::where('Name', $name)->first();
    }
}
