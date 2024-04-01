<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\DataType;
use App\Models\Template;
use App\Models\Validation;
use Illuminate\Http\Request;
use App\Models\TemplateField;
use App\Models\TemplatePayload;
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
    $fieldsFromRequest = collect ($request->fields);
    $fieldIds = $fieldsFromRequest?->pluck('field_id')->toArray();

    TemplateField::where('template_id', $request->id)->whereNotIn('id', $fieldIds)->delete();

    $record = Template::find($request->id);
    $record->template_name =  $request->template_name;
    $record->save();

    // foreach($record_arr as $val){
    //     foreach ($arr as $data) {
    //         if ($data["field_id"] != $val["id"]){
    //             $val->delete();            
    //         }  
    //     }
    // }

    foreach ($request->fields as $data) {
        $field = TemplateField::find($data['field_id']);

        if (!$field) {
            $field = new TemplateField();
            $field->template_id = $request->template_id;
            $field->id =
            $data["field_id"];
        }

        $field->field_name = $data["field_name"];
    
        if (DataType::find($data["data_type"])) {
            $field->data_type_id = $data["data_type"];
        } else {
            return "Data Type Not Found !";
        }

        $field->is_mandatory = $data["is_mandatory"];

        if (Validation::find($data["validations"])){
            $field->validation_id = $data["validations"];
        } else {
            return "Validation id Not Found !";
        }
        $field->template_id = $request->id; 
        $field->save();
    }
} catch (Throwable $e) {
    Log::error($e->getMessage(), [$e->getTraceAsString()]);
    throw new HttpResponseException(response()->json(['status' => false , 'message' => $e->getMessage(), 'errors' => $e->getTraceAsString()]));
}
    return "Data updated !" ;
}

    public function payload(Request $request){
        $arr = $request->fields;
        $obj = new TemplatePayload();
        $obj->template_id = $request->id;
        $obj->payload =[];
        foreach($arr as $data){
            $data_type_id =TemplateField::find($data['field_id'])->data_type_id;
            $data_type_name =  DataType::find($data_type_id)->data_type_name;
            $validation_id =TemplateField::find($data['field_id'])->validation_id;
            $validation_name = Validation::find($validation_id)->validation_name;

            $is_mandatory =TemplateField::find($data['field_id'])->is_mandatory;
            if($is_mandatory){
                if(!$data['value']){
                    return $data['field_name'] ." field is mandatory!";
                }
                if($data_type_name == gettype($data['value']))
                {        
                    $obj->payload += [
                        $data['field_name'] => $data['value'],
                        ];
                    $obj->save();
                }else {
                    return $data['value'] . " is not ". $data_type_name;
                }
            } else{
                if($data['value']){
                    $obj->payload += [
                    $data['field_name'] => $data['value'],
                    ];
                    $obj->save();
                }
            }
        }
       return "Payload Added";
    }


    public function delete(Request $request){
        $template_record = Template::find($request->template_id);
        //$record = TemplateField::find($request->field_id);
        if ($template_record){
            return "Deleted";
        } else{
            return "No template Found";
        }
    }
}

