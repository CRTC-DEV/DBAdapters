<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Stand extends Model
{
    protected $table = 'Stand';
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
     * Get all stands
     */
    public function getAllStands()
    {
        return self::orderBy('Name', 'asc')->get();
    }

    /**
     * Insert new stand
     */
    public function insertStand($data)
    {
        return self::create($data);
    }

    /**
     * Update stand by Id
     */
    public function updateStand($data, $id)
    {
        return self::where('Id', $id)->update($data);
    }

    /**
     * Get stand by name
     */
    public function getStandByName($name)
    {
        return self::where('Name', $name)->first();
    }
}
