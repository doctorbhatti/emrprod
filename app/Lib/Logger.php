<?php

namespace App\Lib;

use App\Models\User;
use Auth;
use Log;

class Logger {

    /**
     * Log an informational message with optional context.
     *
     * @param string $message The message to log.
     * @param array $array Optional context array to log.
     */
    public static function info($message, array $array = []) {
        $user = User::getCurrentUser();
        $context = Auth::check() && $user
            ? ["CLINIC:" . $user->clinic->id, "USER:" . $user->id, $message]
            : [$message];

        Log::info(implode(", ", $context), $array);
    }

    /**
     * Log an error message with optional context.
     *
     * @param string $message The message to log.
     * @param array $array Optional context array to log.
     */
    public static function error($message, array $array = []) {
        $user = User::getCurrentUser();
        $context = Auth::check() && $user
            ? ["CLINIC:" . $user->clinic->id, "USER:" . $user->id, $message]
            : [$message];

        Log::error(implode(", ", $context), $array);
    }
}
