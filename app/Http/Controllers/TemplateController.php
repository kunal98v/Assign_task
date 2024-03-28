<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TemplateField;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TemplateController extends Controller
{
    public function create(Request $request){
        try{
    
                $rules = [
                'template_name'=> 'required',
                'fields.*.field_name' => 'required',
                'fields.*.data_type'=>'required',
                'fields.*.is_mandatory' => 'required',
                'fields.*.validations'=>'required',
            ];
           
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails() ) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }   

        $obj = new Template();
        $obj->template_name =  $request->template_name;
        $obj->status = 1;
        $obj->save();
        $arr = $request->fields;

        foreach ($arr as $data) {
           $field = new TemplateField();
           $field->is_mandatory = $data['is_mandatory'];
           $field->field_name = $data['field_name'];
           $field->template_id = $obj->id; 
           $field->data_type_id = $data['data_type'];  
           $field->validation_id = $data['validations']; 
           $field->save();
        
           $obj->template_fields()->save($field);
        }
    }  
    catch (Throwable $e) {
        Log::error($e->getMessage(), [$e->getTraceAsString()]);
        throw new HttpResponseException(response()->json(['status' => false , 'message' => 'Something Went Wrong', 'errors' => $e->getTraceAsString()]));
    }
    return true;
    }
}

