<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $request->replace($this->transformedInputs($request, $transformer));

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            return $this->transformedErrors(
                $response->getData(),
                $transformer,
                $response
            );
        }
    }

    private function transformedInputs($request, $transformer)
    {
        $transformedInput = [];

        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttributes($input)] = $value;
        }

        return $transformedInput;
    }

    private function transformedErrors($data, $transformer, $response)
    {
        $transformedErrors = [];

        foreach ($data->error as $field => $error) {
            $transformedField = $transformer::transformedAttributes($field);
            $transformedErrors[$transformedField] = str_replace(
                $field, $transformedField, $error
            );
            $data->error = $transformedErrors;
            $response->setData($data);
        }

        return $response;
    }
}
