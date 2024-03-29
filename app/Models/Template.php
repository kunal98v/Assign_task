<?php

namespace App\Models;

use App\Models\TemplateField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;
    function template_fields(){
        return $this->hasMany(TemplateField::class);
    }

}
