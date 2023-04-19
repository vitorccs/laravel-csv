# Laravel CSV
PHP Laravel package to create CSV files in a memory-optimized way.

# Description
Generate CSV files from any of these data sources: _PHP arrays_, _Laravel Collections_ or _Laravel Queries_. You can either prompt the user to download the file or store it in a Laravel disk. For larger datasets, you can generate the file in background as a Laravel Job.

This project was inspired on https://github.com/maatwebsite/Laravel-Excel which is a great project and can handle many formats (Excel, PDF, OpenOffice and CSV). But since it uses PhpSpreadsheet, it is not optimized for exporting large CSV files (thousands of records) causing the PHP memory exhaustion.

The memory usage is optimized in this project by retrieving small chunks of results at a time and outputting the CSV content directly to the client browser or to a persistent file with the use of [PHP streams](https://www.php.net/manual/en/intro.stream.php).

This project is using some of Laravel-Excel design principles because it is both a solid work and a reference, and by doing that, it also reduces the learning curve and adoption to this library.

# Requirements
* PHP >= 7.4
* Laravel >= 6.x

# Installation
Step 1) Add composer dependency
```bash
composer require vitorccs/laravel-csv
```

Step 2) Publish the config file
```bash
php artisan vendor:publish --provider="Vitorccs\LaravelCsv\ServiceProviders\CsvServiceProvider" --tag=config
```

Step 3) Edit your local `config\csv.php` file per your project preferences

Step 4) Create an Export class file as shown below

Note: you may implement _FromArray_, _FromCollection_ or _FromQuery_ 

```php
namespace App\Exports;

use App\User;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;

class UsersExport implements FromQuery
{
    use Exportable;
    
    public function query()
    {
        return User::query();
    }
}
```

Step 5) The file can now be generated by using a single line:
```php
# prompt the client browser to download the file 
return (new UsersExport)->download('users.csv');
```

In case you want the file to be stored in the disk:
```php
# will save the file in 's3' disk
return (new UsersExport)->store('users.csv', 's3');
```

For larger files, you may want to generate the file in background as a Laravel Job
```php
# generate a {uuid-v4}.csv filename
$filename = CsvHelper::filename();

# will create a job to create and store the file in disk
# and afterwards notify the user
(new BillsExport())
    ->queue($filename, 's3')
    ->allOnQueue('default')
    ->chain([
        new NotifyCsvCreated($filename)
    ]);
```

# Data sources
Note: only `FromQuery` can chunk results per `chunk_size` parameter from config file.

## Laravel Eloquent Query Builder
```php
namespace App\Exports;

use App\User;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;

class MyQueryExport implements FromQuery
{
    use Exportable;
    
    public function query()
    {
        return User::query();
    }
}
```

## Laravel Database Query Builder
```php
namespace App\Exports;

use App\User;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class MyQueryExport implements FromQuery
{
    use Exportable;
    
    public function query()
    {
        return DB::table('users');
    }
}
```

## Laravel Collection 
```php
namespace App\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromCollection;

class MyCollectionExport implements FromCollection
{
    use Exportable;
    
    public function collection()
    {
        return collect([
            ['a1', 'b1', 'c1'],
            ['a2', 'b2', 'c2'],
            ['a3', 'b3', 'c3']
        ]);
    }
}
```

## Laravel LazyCollection
```php
namespace App\Exports;

use App\User;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromCollection;

class MyQueryExport implements FromCollection
{
    use Exportable;
    
    public function collection()
    {
        return User::cursor();
    }
}
```

## PHP Arrays
```php
namespace App\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;

class MyArrayExport implements FromArray
{
    use Exportable;
    
    public function array(): array
    {
        return [
            ['a1', 'b1', 'c1'],
            ['a2', 'b2', 'c2'],
            ['a3', 'b3', 'c3']
        ];
    }
}
```

# Implementations

## Headings
`WithHeadings` adds a heading row

```php
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;

class UsersExport implements FromArray, WithHeadings
{
    use Exportable;
    
    public function headings(): array
    {
        return ['ID', 'Name', 'Email'];
    }
}
```

## Mapping rows
Implement `WithMapping` if you either need to set the value of each column or apply some custom formatting. 

```php
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\WithMapping;

class UsersExport implements FromArray, WithMapping
{
    use Exportable;
    
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email ?: 'N/A'
        ];
    }
}
```

## Formatting columns
Implement `WithColumnFormatting` to format Date and Number fields. 
Note: The Date must be a Carbon or Datetime object, and the number must be numeric string, integer or float. The formatting preferences are set in the config file `csv.php`.

```php
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\WithColumnFormatting;
use Vitorccs\LaravelCsv\Enum\CellFormat;
use Carbon\Carbon;

class UsersExport implements FromArray, WithColumnFormatting
{
    use Exportable;
    
    public function array(): array
    {
        return [
            [ Carbon::now(), Carbon::now(), 2.50, 1.00 ],
            [ new DateTime(), new DateTime(), 3, 2.00 ]
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'A' => CellFormat::DATE,
            'B' => CellFormat::DATETIME,
            'C' => CellFormat::DECIMAL,
            'D' => CellFormat::INTEGER,
        ];
    }
}
```

## Limiting the results
Implement the method below if you need to limit the quantity of results.

```php
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;

class UsersExport implements FromQuery
{
    use Exportable;
    
    public function limit(): ?int
    {
        return 5000;
    }
}
```

## License
Released under the [MIT License](LICENSE).
