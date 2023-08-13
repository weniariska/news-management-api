<?php

/**
 * Response json
 *
 * @return response()
 */
if (!function_exists('trueResponse')) {
    function trueResponse($message, $status_code)
    {
        return response()->json([
            'status'=>true,
            'message'=> $message,
        ], $status_code);
    }
}

if (!function_exists('falseResponse')) {
    function falseResponse($message, $status_code, $errors=null)
    {
        if ($errors) {
            return response()->json([
                'status' => false,
                'message' => $message,
                'errors' => $errors,
            ], $status_code);
        } else {
            return response()->json([
                'status' => false,
                'message' => $message,
            ], $status_code);
        }
    }
}