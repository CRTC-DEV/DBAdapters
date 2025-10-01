<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TagRecheck extends Model
{
    protected $table = 'TagRecheck';
    protected $primaryKey = 'Id';

    // Disable automatic timestamps since we have custom timestamp fields
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
     * Get TagRecheck records by FlightId and date range
     */
    public function getTagRecheckByFlight($flightId, $startDate, $endDate)
    {
        return self::where('FlightId', $flightId)
            ->where('ScheduledDatetime', '>=', $startDate)
            ->where('ScheduledDatetime', '<=', $endDate)
            ->where('IsDelete', 0)
            ->orderBy('ScheduledDatetime', 'asc')
            ->get();
    }

    /**
     * Insert new TagRecheck record
     */
    public function insertTagRecheck($data)
    {
        return self::create($data);
    }

    /**
     * Update TagRecheck record by Id
     */
    public function updateTagRecheck($data, $id)
    {
        return self::where('Id', $id)->update($data);
    }

    /**
     * Soft delete TagRecheck record
     */
    public function deleteTagRecheck($id)
    {
        return self::where('Id', $id)->update(['IsDelete' => 1]);
    }

    /**
     * Get TagRecheck records for API with pagination
     */
    public function getTagRecheckAPI($startDate, $endDate, $limit = 100)
    {
        return self::where('ScheduledDatetime', '>=', $startDate)
            ->where('ScheduledDatetime', '<=', $endDate)
            ->where('IsDelete', 0)
            ->orderBy('ScheduledDatetime', 'desc')
            ->limit($limit)
            ->get();
    }
}
