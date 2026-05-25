<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

class StyledXlsxExporter
{
    public static function download(
        string $filename,
        string $title,
        array $columns,
        array $rows,
        array $metadataLines = [],
        array $footerRows = []
    ): StreamedResponse {
        $workbook = self::buildWorkbook($title, $columns, $rows, $metadataLines, $footerRows);

        return response()->streamDownload(function () use ($workbook): void {
            echo $workbook;
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private static function buildWorkbook(string $title, array $columns, array $rows, array $metadataLines, array $footerRows): string
    {
        $tempRoot = self::createTempDirectory();

        try {
            self::writePackageFiles($tempRoot, $title, $columns, $rows, $metadataLines, $footerRows);

            $zipPath = $tempRoot . '.xlsx';
            self::createZipArchive($tempRoot, $zipPath);

            return file_get_contents($zipPath) ?: '';
        } finally {
            self::removeDirectory($tempRoot);
        }
    }

    private static function buildSheetXml(string $title, array $columns, array $rows, array $metadataLines, array $footerRows): string
    {
        $columnCount = max(1, count($columns));
        $lastColumn = self::columnLetter($columnCount);
        $headerRowNumber = 3 + count($metadataLines);
        $dataStartRow = $headerRowNumber + 1;
        $dataEndRow = $dataStartRow + max(0, count($rows) - 1);
        $hasFooterRows = count($footerRows) > 0;
        $footerStartRow = $hasFooterRows ? $dataEndRow + 2 : null;
        $filterEndRow = max($headerRowNumber, $dataEndRow);
        $dimensionEndRow = max($dataEndRow, $headerRowNumber, 2 + count($metadataLines));

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $xml .= '<dimension ref="A1:' . $lastColumn . $dimensionEndRow . '"/>';
        $xml .= '<sheetViews><sheetView workbookViewId="0"><pane ySplit="' . max(1, $headerRowNumber - 1) . '" topLeftCell="A' . $headerRowNumber . '" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>';
        $xml .= '<sheetFormatPr defaultRowHeight="20"/>';

        $xml .= '<cols>';
        foreach ($columns as $index => $column) {
            $width = (float) ($column['width'] ?? 18);
            $columnNumber = $index + 1;

            $xml .= '<col min="' . $columnNumber . '" max="' . $columnNumber . '" width="' . self::formatNumber($width, 1) . '" customWidth="1"/>';
        }
        $xml .= '</cols>';

        $xml .= '<sheetData>';
        $xml .= self::sheetRow(1, [self::inlineStringCell('A1', $title, 1)], $columnCount, 24, true);

        foreach ($metadataLines as $offset => $line) {
            $rowNumber = 2 + $offset;
            $xml .= self::sheetRow($rowNumber, [self::inlineStringCell('A' . $rowNumber, $line, 2)], $columnCount, 19, true);
        }

        $headerCells = [];
        foreach ($columns as $index => $column) {
            $reference = self::cellReference($index + 1, $headerRowNumber);
            $headerCells[] = self::inlineStringCell($reference, (string) ($column['heading'] ?? ''), 3);
        }
        $xml .= self::sheetRow($headerRowNumber, $headerCells, $columnCount, 22, false);

        foreach ($rows as $index => $row) {
            $rowNumber = $dataStartRow + $index;
            $xml .= self::sheetRow($rowNumber, self::dataRowCells($columns, $row, $rowNumber), $columnCount, 20, false);
        }

        if ($hasFooterRows) {
            $xml .= self::sheetRow($dataEndRow + 1, [], $columnCount, 8, false);

            foreach ($footerRows as $offset => $row) {
                $rowNumber = $footerStartRow + $offset;
                $xml .= self::sheetRow($rowNumber, self::footerRowCells($columns, $row, $rowNumber), $columnCount, 20, false);
            }
        }

        $xml .= '</sheetData>';

        $mergeCells = [];

        if ($columnCount > 1) {
            $mergeCells[] = 'A1:' . $lastColumn . '1';

            foreach ($metadataLines as $offset => $_line) {
                $rowNumber = 2 + $offset;
                $mergeCells[] = 'A' . $rowNumber . ':' . $lastColumn . $rowNumber;
            }
        }

        if ($mergeCells !== []) {
            $xml .= '<mergeCells count="' . count($mergeCells) . '">';

            foreach ($mergeCells as $mergeCell) {
                $xml .= '<mergeCell ref="' . $mergeCell . '"/>';
            }

            $xml .= '</mergeCells>';
        }

        $xml .= '<autoFilter ref="A' . $headerRowNumber . ':' . $lastColumn . $filterEndRow . '"/>';
        $xml .= '</worksheet>';

        return $xml;
    }

    private static function dataRowCells(array $columns, array $row, int $rowNumber): array
    {
        $cells = [];
        $rowStyle = ($rowNumber % 2 === 0) ? 5 : 4;

        foreach ($columns as $index => $column) {
            $reference = self::cellReference($index + 1, $rowNumber);
            $value = $row[$index] ?? null;
            $type = $column['type'] ?? 'text';

            if ($value === null || $value === '') {
                continue;
            }

            $cells[] = self::valueCell($reference, $value, $type, match ($type) {
                'currency' => $rowStyle === 5 ? 7 : 6,
                'number' => $rowStyle === 5 ? 7 : 6,
                default => $rowStyle,
            });
        }

        return $cells;
    }

    private static function footerRowCells(array $columns, array $row, int $rowNumber): array
    {
        $cells = [];

        foreach ($columns as $index => $column) {
            $reference = self::cellReference($index + 1, $rowNumber);
            $value = $row[$index] ?? null;
            $type = $column['type'] ?? 'text';

            if ($value === null || $value === '') {
                continue;
            }

            $cells[] = self::valueCell($reference, $value, $type, match ($type) {
                'currency' => 8,
                'number' => 8,
                default => 8,
            });
        }

        return $cells;
    }

    private static function sheetRow(int $rowNumber, array $cells, int $columnCount, int $rowHeight = 20, bool $customHeight = false): string
    {
        $xml = '<row r="' . $rowNumber . '" spans="1:' . $columnCount . '" ht="' . self::formatNumber((float) $rowHeight, 1) . '" customHeight="' . ($customHeight ? '1' : '0') . '">';

        foreach ($cells as $cellXml) {
            $xml .= $cellXml;
        }

        $xml .= '</row>';

        return $xml;
    }

    private static function inlineStringCell(string $reference, string $value, int $styleIndex): string
    {
        return '<c r="' . $reference . '" s="' . $styleIndex . '" t="inlineStr"><is><t>' . self::escapeXml($value) . '</t></is></c>';
    }

    private static function valueCell(string $reference, mixed $value, string $type, int $styleIndex): string
    {
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format('Y-m-d H:i:s');
            $type = 'text';
        }

        if (is_string($value) && str_starts_with($value, '=')) {
            return '<c r="' . $reference . '" s="' . $styleIndex . '"><f>' . self::escapeXml(ltrim($value, '=')) . '</f></c>';
        }

        if ($type === 'currency' || $type === 'number') {
            return '<c r="' . $reference . '" s="' . $styleIndex . '"><v>' . self::formatNumber((float) $value, $type === 'currency' ? 2 : 0) . '</v></c>';
        }

        if ($type === 'boolean') {
            return '<c r="' . $reference . '" s="' . $styleIndex . '" t="b"><v>' . ((bool) $value ? '1' : '0') . '</v></c>';
        }

        return self::inlineStringCell($reference, (string) $value, $styleIndex);
    }

    private static function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/[Content_Types].xml" ContentType="application/vnd.openxmlformats-package.content-types+xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private static function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private static function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Report" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private static function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private static function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="#,##0.00"/></numFmts>'
            . '<fonts count="4">'
            . '<font><sz val="11"/><color rgb="FF3F1D28"/><name val="Calibri"/><family val="2"/></font>'
            . '<font><b/><sz val="16"/><color rgb="FF650C2A"/><name val="Calibri"/><family val="2"/></font>'
            . '<font><sz val="11"/><color rgb="FF3F1D28"/><name val="Calibri"/><family val="2"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/><family val="2"/></font>'
            . '</fonts>'
            . '<fills count="4">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFF9ECF2"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF650C2A"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="3">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left style="thin"><color rgb="FFF3C9D9"/></left><right style="thin"><color rgb="FFF3C9D9"/></right><top style="thin"><color rgb="FFF3C9D9"/></top><bottom style="thin"><color rgb="FFF3C9D9"/></bottom><diagonal/></border>'
            . '<border><left style="thin"><color rgb="FFF3C9D9"/></left><right style="thin"><color rgb="FFF3C9D9"/></right><top style="thin"><color rgb="FFF3C9D9"/></top><bottom style="double"><color rgb="FFF3C9D9"/></bottom><diagonal/></border>'
            . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="9">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="1" fillId="0" borderId="2" xfId="0" applyFont="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="2" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="3" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="2" borderId="1" xfId="0" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyNumberFormat="1"><alignment horizontal="right" vertical="center"/></xf>'
            . '<xf numFmtId="164" fontId="0" fillId="2" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyNumberFormat="1"><alignment horizontal="right" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="2" xfId="0" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private static function writePackageFiles(string $root, string $title, array $columns, array $rows, array $metadataLines, array $footerRows): void
    {
        $directories = [
            $root,
            $root . DIRECTORY_SEPARATOR . '_rels',
            $root . DIRECTORY_SEPARATOR . 'xl',
            $root . DIRECTORY_SEPARATOR . 'xl' . DIRECTORY_SEPARATOR . '_rels',
            $root . DIRECTORY_SEPARATOR . 'xl' . DIRECTORY_SEPARATOR . 'worksheets',
        ];

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
        }

        file_put_contents($root . DIRECTORY_SEPARATOR . '[Content_Types].xml', self::contentTypesXml());
        file_put_contents($root . DIRECTORY_SEPARATOR . '_rels' . DIRECTORY_SEPARATOR . '.rels', self::relsXml());
        file_put_contents($root . DIRECTORY_SEPARATOR . 'xl' . DIRECTORY_SEPARATOR . 'workbook.xml', self::workbookXml());
        file_put_contents($root . DIRECTORY_SEPARATOR . 'xl' . DIRECTORY_SEPARATOR . '_rels' . DIRECTORY_SEPARATOR . 'workbook.xml.rels', self::workbookRelsXml());
        file_put_contents($root . DIRECTORY_SEPARATOR . 'xl' . DIRECTORY_SEPARATOR . 'styles.xml', self::stylesXml());
        file_put_contents($root . DIRECTORY_SEPARATOR . 'xl' . DIRECTORY_SEPARATOR . 'worksheets' . DIRECTORY_SEPARATOR . 'sheet1.xml', self::buildSheetXml($title, $columns, $rows, $metadataLines, $footerRows));
    }

    private static function createZipArchive(string $sourceDirectory, string $zipPath): void
    {
        $scriptPath = tempnam(sys_get_temp_dir(), 'cp_zip_');
        $archivePath = $zipPath . '.zip';

        if ($scriptPath === false) {
            throw new \RuntimeException('Unable to create temporary ZIP script.');
        }

        $powershellPath = $scriptPath . '.ps1';

        if (! rename($scriptPath, $powershellPath)) {
            throw new \RuntimeException('Unable to prepare temporary PowerShell script.');
        }

        $powershellScript = <<<'PS'
param(
    [string]$SourceDir,
    [string]$OutputPath
)

Add-Type -AssemblyName System.IO.Compression.FileSystem

if (Test-Path $OutputPath) {
    Remove-Item $OutputPath -Force
}

[System.IO.Compression.ZipFile]::CreateFromDirectory($SourceDir, $OutputPath, [System.IO.Compression.CompressionLevel]::Optimal, $false)
PS;

        file_put_contents($powershellPath, $powershellScript);

        $command = 'powershell -NoProfile -ExecutionPolicy Bypass -File ' . escapeshellarg($powershellPath)
            . ' -SourceDir ' . escapeshellarg($sourceDirectory)
            . ' -OutputPath ' . escapeshellarg($archivePath);

        $output = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        unlink($powershellPath);

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        if (file_exists($archivePath)) {
            rename($archivePath, $zipPath);
        }

        if ($exitCode !== 0 || ! file_exists($zipPath)) {
            throw new \RuntimeException('Unable to build a valid XLSX archive.');
        }
    }

    private static function createTempDirectory(): string
    {
        $base = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'crafted_pieces_xlsx_' . bin2hex(random_bytes(6));

        if (! mkdir($base, 0777, true) && ! is_dir($base)) {
            throw new \RuntimeException('Unable to create a temporary export directory.');
        }

        return $base;
    }

    private static function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $items = scandir($directory);

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                self::removeDirectory($path);
                continue;
            }

            if (file_exists($path)) {
                unlink($path);
            }
        }

        rmdir($directory);
    }

    private static function columnLetter(int $index): string
    {
        $letter = '';

        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    private static function cellReference(int $columnIndex, int $rowNumber): string
    {
        return self::columnLetter($columnIndex) . $rowNumber;
    }

    private static function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private static function formatNumber(float $value, int $decimals): string
    {
        return number_format($value, $decimals, '.', '');
    }
}