<?php

namespace Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingPeriodRequest extends FormRequest
{
    /**
     * @OA\Schema(
     *   schema="StoreTrainingPeriodRequest",
     *   @OA\Property( title="Name in Spanish", property="name_spanish", description="sub content name in spanish ", format="string", example="Nuevo Periodo" ),
     *   @OA\Property( title="Name in English", property="name_english", description="sub content name in English", format="string", example="New Period" ),
     *   @OA\Property( title="Code", property="code", description="code to identify", format="string", example="new" ),
     * * )
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_spanish' => 'required|string',
            'name_english' => 'required|string',
            'code' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}