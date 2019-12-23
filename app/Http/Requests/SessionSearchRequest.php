<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;

class SessionSearchRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        	'id' => [
        		'required',
				function ($attribute, $value, $fail) {
					try {
						$steamid = steamid64($value);
					} catch (Exception $e) {
						// TODO: use translations
						$id = trim(e($value));
						// TODO: ew
						$fail("<pre class='inline font-bold text-red-950'>$id</pre> não é uma Steam ID válida!");
					}
				}
			],
        ];
    }
}
