<?php
/**
 * Created by PhpStorm.
 * User: Courtney1
 * Date: 11/2/16
 * Time: 2:04 PM
 */

/**
 * API Request Caching
 *
 *  Use server-side caching to store API request's as JSON at a set
 *  interval, rather than each pageload.
 *
 * from: https://www.kevinleary.net/api-request-caching-json-php/
 */
function json_cached_api_results( $cache_file = NULL, $expires = NULL, $pokeapi_url ) {
    global $request_type, $purge_cache, $limit_reached, $request_limit;

    if( !$cache_file ) $cache_file = dirname(__FILE__) . '/../cache/api-cache.json';
    if( !$expires) $expires = time() - 2*60*60;

    if( !file_exists($cache_file) ) die("Cache file is missing: $cache_file");

    // Check that the file is older than the expire time and that it's not empty
    if ( filectime($cache_file) < $expires || file_get_contents($cache_file)  == '' || $purge_cache ) {

        // File is too old, refresh cache
        $api_results = file_get_contents($pokeapi_url);
        $json_results = $api_results;

        // Remove cache file on error to avoid writing wrong xml
        if ( $api_results && $json_results )
            file_put_contents($cache_file, $json_results);
        else
            unlink($cache_file);
    } else {
        // Fetch cache
        $json_results = file_get_contents($cache_file);
        $request_type = 'JSON';
    }

    return json_decode($json_results, true);
    //return $json_results;
}

function json_cached_api_results_items( $cache_file = NULL, $expires = NULL, $pokeapi_url ) {
    global $request_type, $purge_cache, $limit_reached, $request_limit;

    if( !$cache_file ) $cache_file = dirname(__FILE__) . '/../cache/items-api-cache.json';
    if( !$expires) $expires = time() - 2*60*60;

    if( !file_exists($cache_file) ) die("Cache file is missing: $cache_file");

    // Check that the file is older than the expire time and that it's not empty
    if ( filectime($cache_file) < $expires || file_get_contents($cache_file)  == '' || $purge_cache ) {

        // File is too old, refresh cache
        $api_results = file_get_contents($pokeapi_url);
        $json_results = $api_results;

        // Remove cache file on error to avoid writing wrong xml
        if ( $api_results && $json_results )
            file_put_contents($cache_file, $json_results);
        else
            unlink($cache_file);
    } else {
        // Fetch cache
        $json_results = file_get_contents($cache_file);
        $request_type = 'JSON';
    }

    return json_decode($json_results, true);
    //return $json_results;
}

function json_cached_api_results_moves( $cache_file = NULL, $expires = NULL, $pokeapi_url ) {
    global $request_type, $purge_cache, $limit_reached, $request_limit;

    if( !$cache_file ) $cache_file = dirname(__FILE__) . '/../cache/moves-api-cache.json';
    if( !$expires) $expires = time() - 2*60*60;

    if( !file_exists($cache_file) ) die("Cache file is missing: $cache_file");

    // Check that the file is older than the expire time and that it's not empty
    if ( filectime($cache_file) < $expires || file_get_contents($cache_file)  == '' || $purge_cache ) {

        // File is too old, refresh cache
        $api_results = file_get_contents($pokeapi_url);
        $json_results = $api_results;

        // Remove cache file on error to avoid writing wrong xml
        if ( $api_results && $json_results )
            file_put_contents($cache_file, $json_results);
        else
            unlink($cache_file);
    } else {
        // Fetch cache
        $json_results = file_get_contents($cache_file);
        $request_type = 'JSON';
    }

    return json_decode($json_results, true);
    //return $json_results;
}