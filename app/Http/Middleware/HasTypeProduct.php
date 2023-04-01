<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TypeProduct;

class HasTypeProduct
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!TypeProduct::first()) {
            return redirect()->route('type-product.list')->with('warning', 'Não há categorias de produtos registradas.');
        }
        return $next($request);
    }

}
