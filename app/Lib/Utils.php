<?php

namespace App\Lib;

use App\Models\Clinic;
use Carbon\Carbon;
use Exception;

/**
 * Class Utils
 * @package App\Lib
 */
class Utils
{
    /**
     * Get the age from a given date of birth.
     * 
     * @param string $date
     * @return string
     */
    public static function getAge($date)
    {
        try {
            $dob = Carbon::parse($date);
            $today = Carbon::today();
            $diff = $dob->diff($today);
            $text = "";

            if ($diff->y > 0) {
                $text .= $diff->y . " yrs";
            }
            if ($diff->y < 5 && $diff->m > 0) {
                $text .= " " . $diff->m . " months";
            }
            if ($diff->y < 1 && $diff->d > 0) {
                $text .= " " . $diff->d . " days";
            }

            return $text ?: "-";
        } catch (Exception $e) {
            return "-";
        }
    }

    /**
     * Get the readable date and time from a timestamp.
     * 
     * @param string|Carbon $timestamp
     * @return string
     */
    public static function getTimestamp($timestamp)
    {
        try {
            $clinic = Clinic::getCurrentClinic();
            $timezone = $clinic->timezone ?? 'UTC';

            if (!$timestamp instanceof Carbon) {
                $timestamp = Carbon::parse($timestamp);
            }

            return $timestamp->timezone($timezone)->format('jS M, Y h:i A');
        } catch (Exception $e) {
            return 'Invalid date';
        }
    }

    /**
     * Get a formatted date.
     * 
     * @param string|Carbon $date
     * @return string
     */
    public static function getFormattedDate($date)
    {
        try {
            $clinic = Clinic::getCurrentClinic();
            $timezone = $clinic->timezone ?? 'UTC';
            
            $date = Carbon::parse($date)->timezone($timezone);
            return $date->format('jS M, Y');
        } catch (Exception $e) {
            return 'Invalid date';
        }
    }

    /**
     * Check if a patient is male.
     * 
     * @param object $patient
     * @return bool
     */
    public static function isMale($patient)
    {
        return $patient->gender === "Male";
    }

    /**
     * Check if a patient is female.
     * 
     * @param object $patient
     * @return bool
     */
    public static function isFemale($patient)
    {
        return $patient->gender === "Female";
    }

    /**
     * Formats numbers by removing trailing zeros after the decimal place.
     * 
     * @param float $num
     * @return float
     */
    public static function getFormattedNumber($num)
    {
        return (float) $num;
    }

    /**
     * The postfix appended to URLs to prevent browser caching.
     * 
     * @param int $length
     * @return string
     */
    public static function getCachePreventPostfix($length = 5)
    {
        return 'rev=' . bin2hex(random_bytes($length));
    }
}
