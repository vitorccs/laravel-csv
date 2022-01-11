<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chunk size
    |--------------------------------------------------------------------------
    |
    | When using FromQuery, the query is automatically chunked.
    | Here you can specify how big the chunk should be.
    |
    */
    'chunk_size' => 1000,

    /*
    |--------------------------------------------------------------------------
    | Disk
    |--------------------------------------------------------------------------
    |
    | Set disk to store CSV file
    |
    */
    'disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | CSV Settings
    |--------------------------------------------------------------------------
    |
    | Configure delimiter and enclosure for CSV file
    |
    */
    'csv_delimiter' => ',',
    'csv_enclosure' => '"',
    'csv_escape' => '\\',
    'csv_bom' => true,

    /*
    |--------------------------------------------------------------------------
    | Data formatter
    |--------------------------------------------------------------------------
    |
    | Configure date and currency format
    |
    */
    'format_date' => 'Y-m-d',
    'format_datetime' => 'Y-m-d H:i:s',
    'format_number_decimals' => 2,
    'format_number_decimal_sep' => '.',
    'format_number_thousand_sep' => ',',

    /*
    |--------------------------------------------------------------------------
    | Job Settings
    |--------------------------------------------------------------------------
    |
    | Configure the Job settings (timeout, attempts, etc)
    |
    */
    'job_timeout' => 60 * 10,
    'job_attempts' => 1,
    'job_delay' => 0
];
