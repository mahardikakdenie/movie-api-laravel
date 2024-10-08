<?php

namespace App\Helpers;

class ResponseFormatter
{
    /**
     * Format the success response for an API request.
     *
     * This method creates a standardized JSON response structure for successful API calls.
     *
     * @param  mixed  $data        The data to be included in the response. Can be null.
     * @param  string $message     A custom success message. If null, a default message will be used.
     * @param  bool   $status      The status of the response. Defaults to true.
     * @param  int    $code        The HTTP status code. Defaults to 200.
     * @param  mixed  $additional  Additional meta information to be included in the response. Can be null.
     * @param  string $group       An optional grouping key for paginated data. Can be null.
     * @return \Illuminate\Http\JsonResponse The formatted JSON response.
     */
    public static function success($data = null, $message = null, $status = true, $code = 200, $additional = null, $group = null)
    {
        if ($message == null) {
            $message = __('message.success');
        }
        if ($data == null) {
            $data = [];
        }
        $result['meta']['status'] = $status;
        $result['meta']['message'] = $message;
        $result['meta']['code'] = $code;
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {

            $pagination = $data->toArray();
            $result['meta']['total'] = $data->total();
            $result['meta']['per_page'] = $data->perPage();
            $result['meta']['current_page'] = $data->currentpage();
            $result['meta']['last_page'] = $data->lastPage();
            $result['meta']['from'] = $pagination['from'];;
            $result['meta']['to'] = $pagination['to'];
            $result['links']['next'] = $data->nextPageUrl();
            $result['links']['prev'] = $data->previousPageUrl();
            $result['links']['first'] = $pagination['first_page_url'];
            $result['links']['last'] = $pagination['last_page_url'];

            if ($group != null) {

                $data = $data->groupBy($group);
                foreach ($data as $key => $value) {
                    $group_item[] = [
                        'group' => $key,
                        'item' => $value
                    ];
                }
                $result['data'] = $group_item;
            } else {
                $result['data'] = $data->all();
            }
        } else {
            $result['data'] = $data;
        }

        if ($additional != null) {
            foreach ($additional as $add) {
                $result['meta'][$add['name']] = $add['data'];
            }
        }
        $code = 200;
        return response()->json($result, $code);
    }

    /**
     * Format the error response for an API request.
     *
     * This method creates a standardized JSON response structure for failed API calls.
     *
     * @param  string|null $message  A custom error message. If null, a default message will be used.
     * @param  mixed       $error    Detailed error information. Can be an instance of ErrorException or other types.
     * @param  bool       $status    The status of the response. Defaults to false.
     * @param  int        $code      The HTTP status code. Defaults to 400.
     * @return \Illuminate\Http\JsonResponse The formatted JSON response.
     */
    public static function error($message = null, $error = null, $status = false, $code = 400)
    {
        if ($message == null) {
            $message = __('message.error');
        }
        $result['data'] = [];
        $result['meta']['status'] = $status;
        $result['meta']['message'] = $message;
        $result['meta']['code'] = $code;
        if ($error instanceof \ErrorException) {
            $result['error']['message'] = $error->getMessage();
            $result['error']['file'] = $error->getFile();
            $result['error']['line'] = $error->getLine();
        } else {
            $result['error'] = $error;
        }
        return response()->json($result, 200);
    }
}
