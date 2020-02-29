<?php

namespace App\Helpers;

use Illuminate\Support\Str;

if (!function_exists('monthAbbreviation')) {
    /**
     * Return the abbreviation of the month based on the given Carbon Date.
     *
     * @param $carbonDate
     * @return string
     */
    function monthAbbreviation($carbonDate)
    {
        $month = $carbonDate->format('m');

        switch ($month) {
            case '01':
                return "Ian";
            case '02':
                return "Feb";
            case '03':
                return "Mart";
            case '04':
                return "Apr";
            case '05':
                return "Mai";
            case '06':
                return "Iun";
            case '07':
                return "Iul";
            case '08':
                return "Aug";
            case '09':
                return "Sept";
            case '10':
                return "Oct";
            case '11':
                return "Nov";
            case '12':
                return "Dec";
        }
    }
}

if (!function_exists('generateRandomString')) {
    /**
     * Generate a random string.
     *
     * @param $length
     * @return string
     */
    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}

if (!function_exists('generateSlug')) {
    /**
     * Generate Slug with 10 random characters appended at the end.
     *
     * @param $input
     * @param int $randomCharacters
     * @return string
     */
    function generateSlug($input, $randomCharacters = 10)
    {
        return Str::slug($input, '-') . "-" . generateRandomString($randomCharacters);
    }
}