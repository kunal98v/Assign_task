<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TemplateField;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TemplateController extends Controller
{
    public function create(Request $request){
        try{
            $rules = [
                'template_name'=> 'required|string',
                'fields.*.field_name' => 'required',
                'fields.*.data_type'=>'required|integer',
                'fields.*.is_mandatory' => 'required|boolean',
                'fields.*.validations'=>'required|integer',
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
    return "DATA ADDED !";
}


public function update(Request $request){    
    try{
        $rules = [
            'template_name'=> 'required |string',
            'fields.*.field_id' => 'required|integer',
            'fields.*.field_name' => 'required',
            'fields.*.data_type'=>'required|integer',
            'fields.*.is_mandatory' => 'required|boolean',
            'fields.*.validations'=>'required |integer',
        ];
       
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails() ) {
        return response()->json(['status' => false, 'errors' => $validator->errors()]);
    }      
    $record = Template::with('template_fields')->find($request->id);
    $record->template_name =  $request->template_name;
    $record->save();
    $arr = $request->fields;
    foreach ($arr as $data) {
        $field = TemplateField::find($data["field_id"]);
        if (!$field) {
            $field = new TemplateField();
            $field->template_id = $record->id; 
        }
            $field->field_name = $data["field_name"];
            $field->data_type_id = $data["data_type"];
            $field->is_mandatory = $data["is_mandatory"];
            $field->validation_id = $data["validations"];
            $field->template_id = $record->id; 
            // $record->template_fields()->save($field);
            $field->save();
    }
}catch (Throwable $e) {
    Log::error($e->getMessage(), [$e->getTraceAsString()]);
    throw new HttpResponseException(response()->json(['status' => false , 'message' => 'Something Went Wrong', 'errors' => $e->getTraceAsString()]));
}
    return "Data updated !";
}



    public function delete(Request $request){
        $template_record = Template::find($request->template_id);
        //$record = TemplateField::find($request->field_id);
        if($template_record){
            try{
            $template_record->delete();

            }catch(Exception){
                return "Cannot delete template before deleting the fields!";
            }
            return "Deleted";
        }
        else
            return "No template Found";
    }
}

