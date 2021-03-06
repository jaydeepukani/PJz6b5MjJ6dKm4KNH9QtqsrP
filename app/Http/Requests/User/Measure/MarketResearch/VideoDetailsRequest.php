<?php

namespace App\Http\Requests\User\Measure\MarketResearch;

use Illuminate\Foundation\Http\FormRequest;

class VideoDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return appUser()->can('measure.market-research.video-details');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
