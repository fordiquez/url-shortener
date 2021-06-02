<?php

namespace App\Http\Requests;

use App\Rules\Blacklist;
use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $uniqueCode = '|unique:short_urls';
        $expires_at_timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') + 3, date('y'));
        $expires_at_date = date('Y-m-d H:i:s', $expires_at_timestamp);

        if ($this->route('id')) {
            $uniqueCode .= ',id,'.$this->route('id');
        }

        return [
            'url'  => ['required', 'url', new Blacklist()],
            'code' => 'max:255'.$uniqueCode,
            'expires_at' => 'date|after:now|before_or_equal:' . $expires_at_date . '|nullable',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $data = parent::validationData();

        $modify = isset($data['code']) ? ['code' => \Str::slug($data['code'])] : [];

        return array_merge($data, $modify);
    }
}
