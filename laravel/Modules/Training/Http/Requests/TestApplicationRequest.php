<?php

namespace Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * * @OA\Schema(
     *   schema="TestApplicationExerciseSessionRequest",
     *   @OA\Property( title="Test", property="test_id", description="Test applied", format="integer", example="1" ),
     *   @OA\Property( title="Entity id", property="applicable_id", description="id to entity", format="string", example="1"  ),
     *   @OA\Property( title="Answers", property="answers", description="answers to test", format="array", example="[{'id':46,'text': 'text response'},{'id':51,'text': '1'}]" ),
     *   @OA\Property( title="Date Application", property="date_application", description="", format="date", example="2021-12-01" ),
     *   @OA\Property( title="Player", property="player_id", description="player associate (send either player_id or alumn_id)", format="integer", example="1" ),
     *   @OA\Property( title="Alumn", property="alumn_id", description="alumn associate (send either player_id or alumn_id)", format="integer", example="1" ),
     *   @OA\Property( title="Test Name", property="test_name", description="type test (send gps or frecuency_cardiac)", format="string", example="gps" ),
     * )
     */
    public function rules()
    {
        return [
            'test_id' => 'required|integer|exists:tests,id',
            'applicable_id' => 'required|integer',
            'answers' => 'required|array',
            'date_application' => 'required|date',
            'player_id' => 'exists:players,id',
            'alumn_id' => 'exists:alumns,id',
            'test_name' => 'string|required|in:gps,frecuency_cardiac'
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