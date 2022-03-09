<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProductsSearchRequest
 *
 * @package App\Http\Requests
 */
class ProductsSearchRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_by' => ['nullable', 'max:255', 'in:date_asc,date_desc,price_asc,price_desc'],
            'category_id' => ['nullable', 'integer', 'min:0'],
            'quality' => ['nullable', 'integer', 'min:0', 'max:1'],
            'offset'  => ['required', 'integer', 'min:0'],
            'limit'  => ['required', 'integer', 'min:1', 'max:200'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'order_by.in' => 'Доступные виды сортировки: date_asc, date_desc, price_asc, price_desc',
        ];
    }
}
