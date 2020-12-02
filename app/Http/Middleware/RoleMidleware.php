<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMidleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
      if (!in_array(auth()->user()->role,$roles)) {
        if (auth()->user()->role == 'cashier') {
          return redirect()->route('transaksi.index');
        }elseif (auth()->user()->role == 'stoker') {
          return redirect()->route('barang.index');
        }
        return redirect()->route('login')->withErrors('Anda tidak memiliki akses!');
      }
      return $next($request);
    }
}
