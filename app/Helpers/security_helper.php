<?php

if (!function_exists('xss_clean_input')) {
    function xss_clean_input(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data =  htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}