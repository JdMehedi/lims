<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {

        if (!in_array(strtolower($request->method()), ['put', 'post','get','patch'])) {
            return $next($request);
        }

        if($_SERVER['REQUEST_URI'] == '/process-path/batch-process-update'){
//            if(!in_array(Encryption::decodeId($request->get('process_type_id')),[])){
            return $next($request);
//            }
        }

        $input = $request->all();

        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
        });

        $request->merge($input);

        return $next($request);
    }
}
