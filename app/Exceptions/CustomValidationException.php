<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class CustomValidationException extends ValidationException
{
    /**
     * Summarize the errors for display.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return string
     */
    protected static function summarize($validator)
    {
        $messages = $validator->errors()->all();

        if (! count($messages) || ! is_string($messages[0])) {
            return $validator->getTranslator()->get('The given data was invalid.');
        }

        $message = array_shift($messages);

        return $message;
    }
}
