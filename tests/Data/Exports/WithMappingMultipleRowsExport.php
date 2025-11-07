<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportables\Exportable;
use Vitorccs\LaravelCsv\Concerns\Exportables\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithMapping;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestCsv;

class WithMappingMultipleRowsExport implements FromQuery, WithMapping
{
	use Exportable;

	public function query()
	{
		return TestCsv::query();
	}

	public function map($row): array
	{
		return [
			[
				$row->id,
				$row->string . '_first',
			],
			[
				$row->id,
				$row->string . '_second',
			],
		];
	}

	public function expected(): string
	{
		return '1,text_1_first' . "\n" .
			'1,text_1_second' . "\n" .
			'2,text_2_first' . "\n" .
			'2,text_2_second' . "\n" .
			'3,text_3_first' . "\n" .
			'3,text_3_second' . "\n" .
			'4,text_4_first' . "\n" .
			'4,text_4_second' . "\n" .
			'5,text_5_first' . "\n" .
			'5,text_5_second' . "\n" .
			'6,text_6_first' . "\n" .
			'6,text_6_second';
	}
}
