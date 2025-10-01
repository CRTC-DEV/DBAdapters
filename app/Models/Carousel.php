<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Carousel extends Model
{
    protected $table = 'Carousel';
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
     * Get all carousels
     */
    public function getAllCarousels()
    {
        return self::orderBy('Name', 'asc')->get();
    }

    /**
     * Insert new carousel
     */
    public function insertCarousel($data)
    {
        return self::create($data);
    }

    /**
     * Update carousel by Id
     */
    public function updateCarousel($data, $id)
    {
        return self::where('Id', $id)->update($data);
    }

    /**
     * Get carousel by name
     */
    public function getCarouselByName($name)
    {
        return self::where('Name', $name)->first();
    }
}
