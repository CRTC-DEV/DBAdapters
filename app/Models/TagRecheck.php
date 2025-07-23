<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TagRecheck extends Model
{
    protected $table = 'TagRecheck'; // Specify the table name

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

    public function getAllTagRecheck()
    {
        $recheckedBags = TagRecheck::all();
        return $recheckedBags;
    }

    public function getTagRecheckByTagNumber($tagnumber)
    {
        $recheckedBags = TagRecheck::where('TagNumber', $tagnumber)->get();
        return $recheckedBags;
    }
}
