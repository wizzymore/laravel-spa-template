<?php

namespace App\Http\Helpers;

class Api
{
    public static function success(array $data = [], int $status = 200)
    {
        return self::response(true, $data, '', $status);
    }

    public static function error(string $message = '', array $errors = [], int $status = 400)
    {
        return self::response(false, $errors, $message, $status);
    }

    public static function response(bool $sucess = true, array $data = [], string $message = '', int $status = 200)
    {
        $response = [
            'success' => $sucess,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data) {
            $key = 'data';

            if (!$sucess) {
                $key = 'errors';
            }

            $response[$key] = $data;
        }

        return response()->json($response, $status);
    }

    public static function notFound()
    {
        abort(404, 'Not found.');
    }
}
