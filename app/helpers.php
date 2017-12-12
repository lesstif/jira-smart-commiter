<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Exceptions\SmartCommitException;
use \GuzzleHttp\Psr7\Response;
use MyCLabs\Enum\Enum;

// HTTP Link Header for pagination
class LINK_HEADER extends Enum {
    const NONE = 'NONE';
    const HAS_NEXT = 'HAS_NEXT';
    const REACH_LAST = 'REACH_LAST';
}

/**
 * Check HTTP Link header and find next entity
 *
 * @param Response $response
 * @param $next_url out next_url
 * @return LINK_HEADER
 */
function has_next_link(Response $response, &$next_url) : LINK_HEADER
{
    // find Link Header for remained data
    $link = $response->getHeader('Link');

    // no more data
    if (empty($link)) {
        debug('Link header is not exist!', $link);

        return LINK_HEADER::NONE();
    }

    $ar = preg_split('/,/', $link[0]);

    $found = false;
    $next = null;

    foreach ($ar as $l) {
        // format: <https://gitlab.example.com/api/v3/projects?page=2&per_page=100>; rel="next"
        //Link: <https://api.github.com/resource?page=2>; rel="next",
        //<https://api.github.com/resource?page=5>; rel="last"
        if (preg_match('/<(.*)>;[ \t]*rel="next"/', $l, $next) === 1) {
            $next_url = $next[1];

            return LINK_HEADER::HAS_NEXT();
        }
    }
    info('we reached last entity! ', $ar);

    return LINK_HEADER::REACH_LAST();
}

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null $tz
     * @return \Illuminate\Support\Carbon
     */
    function today($tz = null)
    {
        return Carbon::today($tz);
    }
}

if (! function_exists('app_path')) {
    function app_path()
    {
        return getenv('HOME').DIRECTORY_SEPARATOR.'.smartcommit';
    }
}

if (! function_exists('is_config_exists')) {
    function is_config_exists($file)
    {
        return file_exists(app_path().DIRECTORY_SEPARATOR.$file);
    }
}

if (! function_exists('config_load')) {
    function config_load($file)
    {
        $content = file_get_contents(full_path($file));

        return $content;
    }
}

if (! function_exists('full_path')) {
    function full_path($file)
    {
        return app_path().DIRECTORY_SEPARATOR.$file;
    }
}

if (! function_exists('config_save')) {

    /**
     * save config file.
     *
     * @param $contents
     * @param $fileName
     * @param $overwrite
     * @return bool|int
     */
    function config_save($contents, $fileName, $overwrite)
    {
        make_app_dir();

        $fullPath = full_path($fileName);

        if (is_config_exists($fullPath) && $overwrite !== true) {
            $now = Carbon::now();
            $now->setToStringFormat('Y-m-d-H-i-s');

            $toFile = $fullPath.'-'.$now;

            rename($fullPath, $toFile);
        }

        return file_put_contents($fullPath, $contents);
    }
}

if (! function_exists('make_app_dir')) {
    function make_app_dir($path = '')
    {
        $main_dir = app_path();

        // check directory exist.
        if (! file_exists($main_dir)) {
            mkdir($main_dir);
        }

        if (! is_dir($main_dir)) {
            throw new SmartCommitException($main_dir.' is not directory!');
        }

        $log_dir = $main_dir.DIRECTORY_SEPARATOR.'logs';
        if (! file_exists($log_dir)) {
            mkdir($log_dir);
        }

        $mutex_dir = $main_dir.DIRECTORY_SEPARATOR.'app';
        if (! file_exists($mutex_dir)) {
            mkdir($mutex_dir);
        }
    }
}

/*
 * log helper
 */
if (! function_exists('msg_format')) {
    function msg_format($message, $additionalData)
    {
        $msg = $message;
        if (is_array($additionalData) || is_object($additionalData)) {
            $msg .= json_encode($additionalData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            $msg .= $additionalData;
        }

        return $msg;
    }
}

if (! function_exists('info')) {
    function info($message, $additionalData)
    {
        Log::info(msg_format($message, $additionalData));
    }
}

if (! function_exists('debug')) {
    function debug($message, $additionalData)
    {
        Log::debug(msg_format($message, $additionalData));
    }
}

if (! function_exists('toIso8601String')) {
    function toIso8601String(?Carbon $dt) : ?string
    {
        if ($dt instanceof Carbon)
            return $dt->toIso8601String();

        return null;
    }
}
