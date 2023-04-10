<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class CheckParamsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ticket'                      => ['required', 'array'],
            'ticket.*.subject'            => ['required'],
            'ticket.*.user_name'          => ['required'],
            'ticket.*.user_email'         => ['required', 'email'],
            'send'                        => ['required', 'array'],
            'send.*.content'              => ['required'],
            'send.*.author'               => ['required'],
            'credentials'                 => ['required', 'array'],
            'credentials.*.ftp_login'     => ['required'],
            'credentials.*.ftp_password'  => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'ticket.required'                       => 'Отсутствует параметр ticket',
            'ticket.array'                          => 'Параметр ticket должен являться массивом',
            'ticket.*.subject.required'             => 'Отсутствует параметр subject',
            'ticket.*.user_name.required'           => 'Отсутствует параметр user_name',
            'ticket.*.user_email.required'          => 'Отсутствует параметр user_email',
            'ticket.*.user_email.email'             => 'Параметр user_email должен являться email',
            'send.array'                            => 'Параметр send должен являться массивом',
            'send.required'                         => 'Отсутствует параметр send',
            'send.*.content.required'               => 'Отсутствует параметр content',
            'send.*.author.required'                => 'Отсутствует параметр author',
            'credentials.required'                  => 'Отсутствует параметр send',
            'credentials.array'                     => 'Параметр credentials должен являться массивом',
            'credentials.*.ftp_login.required'      => 'Отсутствует параметр ftp_login',
            'credentials.*.ftp_password.required'   => 'Отсутствует параметр ftp_password',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
