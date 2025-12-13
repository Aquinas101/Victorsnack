<?php

if (!function_exists('role_route')) {
    /**
     * Generate route name berdasarkan role user yang login
     * 
     * @param string $name - nama route tanpa prefix (contoh: 'produk.index')
     * @return string - route name dengan prefix role (contoh: 'admin.produk.index')
     */
    function role_route($name)
    {
        $role = auth()->user()->role ?? 'guest';
        
        $prefix = match($role) {
            'pemilik' => 'admin',
            'karyawan' => 'karyawan',
            'kasir' => 'kasir',
            default => ''
        };
        
        return $prefix ? "{$prefix}.{$name}" : $name;
    }
}