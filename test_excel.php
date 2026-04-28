<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Col1');
$sheet->setCellValue('B1', 'Col2');
$sheet->setCellValue('A2', 'Data1');
$sheet->setCellValue('B2', 'Data2');

$table = new Table('A1:B10', 'MyTable');
$tableStyle = new TableStyle();
$tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
$tableStyle->setShowRowStripes(true);
$table->setStyle($tableStyle);
$sheet->addTable($table);

$writer = new Xlsx($spreadsheet);
$writer->save('test_table.xlsx');
echo "Done";
