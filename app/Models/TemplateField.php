<?php

namespace App\Models;

use App\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateField extends Model
{
    use HasFactory;
    function template(){
        return $this->belongsTo(Template::class);
    }
}
