<?php

declare(strict_types=1);

final class AcademicPdf
{
    private const WIDTH = 595.28;
    private const HEIGHT = 841.89;

    private array $pages = [];
    private int $pageIndex = -1;
    private int $pageNumber = 0;

    public function addPage(string $runningTitle = 'PeopleOps HRMS'): void
    {
        $this->pages[] = '';
        $this->pageIndex++;
        $this->pageNumber++;

        if ($this->pageNumber > 1) {
            $this->setStroke(225, 231, 239);
            $this->line(48, 64, self::WIDTH - 48, 64);
            $this->text(48, 45, $runningTitle, 'Helvetica-Bold', 8.5, [76, 88, 107]);
            $this->text(self::WIDTH - 120, 45, 'Academic Software Report', 'Helvetica', 8.5, [100, 116, 139]);
            $this->setStroke(225, 231, 239);
            $this->line(48, self::HEIGHT - 54, self::WIDTH - 48, self::HEIGHT - 54);
            $this->text(48, self::HEIGHT - 34, 'PeopleOps HRMS', 'Helvetica', 8.5, [100, 116, 139]);
            $this->text(self::WIDTH - 70, self::HEIGHT - 34, (string) $this->pageNumber, 'Helvetica-Bold', 8.5, [76, 88, 107]);
        }
    }

    public function save(string $path): void
    {
        $objects = [
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Times-Roman /Encoding /WinAnsiEncoding >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Courier /Encoding /WinAnsiEncoding >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique /Encoding /WinAnsiEncoding >>',
        ];

        $catalogObject = 6;
        $pagesObject = 7;
        $firstPageObject = 8;
        $kids = [];
        $pageObjectNumber = $firstPageObject;
        foreach ($this->pages as $i => $stream) {
            $kids[] = $pageObjectNumber . ' 0 R';
            $pageObjectNumber += 2;
        }

        $objects[] = "<< /Type /Catalog /Pages {$pagesObject} 0 R >>";
        $objects[] = "<< /Type /Pages /Kids [" . implode(' ', $kids) . "] /Count " . count($this->pages) . " >>";

        $contentObjectNumber = $firstPageObject + 1;
        foreach ($this->pages as $stream) {
            $objects[] = "<< /Type /Page /Parent {$pagesObject} 0 R /MediaBox [0 0 " . self::WIDTH . ' ' . self::HEIGHT . "] /Resources << /Font << /F1 1 0 R /F2 2 0 R /F3 3 0 R /F4 4 0 R /F5 5 0 R >> >> /Contents {$contentObjectNumber} 0 R >>";
            $objects[] = "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}endstream";
            $contentObjectNumber += 2;
        }

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0];

        foreach ($objects as $number => $body) {
            $objectNumber = $number + 1;
            $offsets[$objectNumber] = strlen($pdf);
            $pdf .= "{$objectNumber} 0 obj\n{$body}\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root {$catalogObject} 0 R >>\n";
        $pdf .= "startxref\n{$xref}\n%%EOF";

        file_put_contents($path, $pdf);
    }

    public function title(string $text, float $y = 96): void
    {
        $this->text(48, $y, $text, 'Helvetica-Bold', 22, [15, 23, 42]);
        $this->setStroke(79, 113, 255);
        $this->line(48, $y + 16, 176, $y + 16);
    }

    public function subtitle(string $text, float $x, float $y, float $size = 13): void
    {
        $this->text($x, $y, $text, 'Helvetica-Bold', $size, [30, 41, 59]);
    }

    public function paragraph(string $text, float $x, float $y, float $width, float $size = 10.3, float $lineHeight = 15.6, array $color = [51, 65, 85]): float
    {
        foreach ($this->wrap($text, $width, $size) as $line) {
            $this->text($x, $y, $line, 'Times-Roman', $size, $color);
            $y += $lineHeight;
        }

        return $y;
    }

    public function bulletList(array $items, float $x, float $y, float $width, float $size = 9.8): float
    {
        foreach ($items as $item) {
            $this->text($x, $y, '-', 'Helvetica-Bold', $size, [79, 113, 255]);
            $wrapped = $this->wrap($item, $width - 18, $size);
            foreach ($wrapped as $i => $line) {
                $this->text($x + 16, $y, $line, 'Times-Roman', $size, [51, 65, 85]);
                if ($i < count($wrapped) - 1) {
                    $y += 13.8;
                }
            }
            $y += 18;
        }

        return $y;
    }

    public function codeBox(string $code, float $x, float $y, float $width, float $height): void
    {
        $this->roundedRect($x, $y, $width, $height, 8, [15, 23, 42], [15, 23, 42]);
        $this->text($x + 16, $y + 22, 'Code excerpt', 'Helvetica-Bold', 9, [147, 197, 253]);

        $lineY = $y + 42;
        foreach (explode("\n", trim($code)) as $line) {
            $line = str_replace("\t", '    ', $line);
            $this->text($x + 16, $lineY, $this->clip($line, 86), 'Courier', 7.6, [226, 232, 240]);
            $lineY += 11;
            if ($lineY > $y + $height - 12) {
                break;
            }
        }
    }

    public function figureCaption(string $caption, float $x, float $y): void
    {
        $this->text($x, $y, $caption, 'Helvetica-Oblique', 8.7, [71, 85, 105]);
    }

    public function architectureDiagram(float $x, float $y): void
    {
        $boxes = [
            ['Browser', 'Responsive HRMS UI', $x, $y, 118, 52, [239, 246, 255]],
            ['Render', 'Nginx + PHP-FPM', $x + 155, $y, 128, 52, [238, 242, 255]],
            ['Laravel', 'Routes, middleware, controllers', $x + 320, $y, 158, 52, [240, 253, 244]],
            ['Supabase', 'PostgreSQL pooler', $x + 320, $y + 105, 158, 52, [255, 247, 237]],
            ['Vite Assets', 'Compiled CSS and JS', $x + 155, $y + 105, 128, 52, [250, 245, 255]],
        ];

        foreach ($boxes as [$title, $body, $bx, $by, $bw, $bh, $fill]) {
            $this->roundedRect($bx, $by, $bw, $bh, 7, $fill, [203, 213, 225]);
            $this->text($bx + 12, $by + 20, $title, 'Helvetica-Bold', 10, [15, 23, 42]);
            $this->text($bx + 12, $by + 36, $body, 'Helvetica', 8, [71, 85, 105]);
        }

        $this->arrow($x + 118, $y + 26, $x + 155, $y + 26);
        $this->arrow($x + 283, $y + 26, $x + 320, $y + 26);
        $this->arrow($x + 399, $y + 52, $x + 399, $y + 105);
        $this->arrow($x + 219, $y + 105, $x + 219, $y + 52);
        $this->text($x + 20, $y + 84, 'HTTPS requests, CSRF-protected forms, session cookies, and route middleware control access.', 'Helvetica', 8.3, [71, 85, 105]);
    }

    public function erDiagram(float $x, float $y): void
    {
        $entities = [
            ['users', ['id', 'role_id', 'employee_code', 'status'], $x + 190, $y, 130, 78],
            ['roles', ['id', 'slug', 'permissions'], $x + 360, $y, 120, 70],
            ['employee_profiles', ['user_id', 'department_id', 'designation_id'], $x + 170, $y + 115, 170, 82],
            ['departments', ['id', 'code', 'manager_user_id'], $x, $y + 120, 135, 78],
            ['designations', ['department_id', 'title', 'grade'], $x, $y + 230, 135, 76],
            ['attendance_records', ['user_id', 'work_date', 'status'], $x + 175, $y + 240, 155, 76],
            ['leave_requests', ['user_id', 'leave_type_id', 'status'], $x + 360, $y + 135, 155, 80],
            ['payrolls', ['user_id', 'pay_period', 'net_pay'], $x + 360, $y + 250, 130, 76],
        ];

        foreach ($entities as [$name, $fields, $bx, $by, $bw, $bh]) {
            $this->roundedRect($bx, $by, $bw, $bh, 5, [248, 250, 252], [203, 213, 225]);
            $this->rect($bx, $by, $bw, 22, [79, 113, 255], [79, 113, 255]);
            $this->text($bx + 8, $by + 15, $name, 'Helvetica-Bold', 8.8, [255, 255, 255]);
            $fy = $by + 36;
            foreach ($fields as $field) {
                $this->text($bx + 10, $fy, $field, 'Courier', 7.5, [51, 65, 85]);
                $fy += 13;
            }
        }

        $this->line($x + 320, $y + 28, $x + 360, $y + 28);
        $this->line($x + 255, $y + 78, $x + 255, $y + 115);
        $this->line($x + 170, $y + 156, $x + 135, $y + 156);
        $this->line($x + 67, $y + 198, $x + 67, $y + 230);
        $this->line($x + 255, $y + 197, $x + 255, $y + 240);
        $this->line($x + 340, $y + 155, $x + 360, $y + 155);
        $this->line($x + 330, $y + 278, $x + 360, $y + 278);
    }

    public function uiMockup(string $screen, float $x, float $y, float $w, float $h): void
    {
        $this->roundedRect($x, $y, $w, $h, 8, [255, 255, 255], [226, 232, 240]);
        $this->rect($x, $y, $w, 30, [248, 250, 252], [226, 232, 240]);
        $this->text($x + 14, $y + 20, 'PeopleOps HRMS - ' . $screen, 'Helvetica-Bold', 9, [30, 41, 59]);

        if ($screen === 'Login') {
            $cx = $x + $w / 2 - 85;
            $this->roundedRect($cx, $y + 58, 170, 160, 8, [255, 255, 255], [226, 232, 240]);
            $this->roundedRect($cx + 65, $y + 25, 40, 40, 8, [79, 113, 255], [79, 113, 255]);
            $this->text($cx + 77, $y + 50, 'PO', 'Helvetica-Bold', 12, [255, 255, 255]);
            $this->text($cx + 45, $y + 82, 'PeopleOps HRMS', 'Helvetica-Bold', 11, [15, 23, 42]);
            $this->formLine($cx + 20, $y + 108, 130, 'Email');
            $this->formLine($cx + 20, $y + 145, 130, 'Password');
            $this->roundedRect($cx + 20, $y + 180, 130, 20, 4, [30, 41, 59], [30, 41, 59]);
            $this->text($cx + 70, $y + 194, 'LOG IN', 'Helvetica-Bold', 7.2, [255, 255, 255]);
            return;
        }

        $sideW = 90;
        $this->rect($x, $y + 30, $sideW, $h - 30, [248, 250, 252], [226, 232, 240]);
        foreach (['Dashboard', 'Employees', 'Departments', 'Attendance', 'Leave', 'Payroll'] as $i => $item) {
            $iy = $y + 55 + ($i * 24);
            if (($screen === 'Dashboard' && $i === 0) || ($screen === 'Employees' && $i === 1) || ($screen === 'Leave' && $i === 4)) {
                $this->roundedRect($x + 10, $iy - 12, $sideW - 20, 18, 4, [79, 113, 255], [79, 113, 255]);
                $this->text($x + 18, $iy, $item, 'Helvetica', 7, [255, 255, 255]);
            } else {
                $this->text($x + 18, $iy, $item, 'Helvetica', 7, [71, 85, 105]);
            }
        }

        $contentX = $x + $sideW + 18;
        $this->text($contentX, $y + 62, $screen === 'Employees' ? 'Employees' : ($screen === 'Leave' ? 'Leave Management' : 'HRMS Dashboard'), 'Helvetica-Bold', 13, [15, 23, 42]);

        if ($screen === 'Dashboard') {
            for ($i = 0; $i < 4; $i++) {
                $this->roundedRect($contentX + $i * 83, $y + 82, 70, 48, 6, [255, 255, 255], [226, 232, 240]);
                $this->text($contentX + $i * 83 + 10, $y + 102, ['Employees', 'Departments', 'Present', 'Leaves'][$i], 'Helvetica', 6.5, [100, 116, 139]);
                $this->text($contentX + $i * 83 + 10, $y + 119, ['10', '5', '11', '2'][$i], 'Helvetica-Bold', 13, [15, 23, 42]);
            }
            $this->tableSkeleton($contentX, $y + 150, 250, 110, ['Employee', 'Department', 'Role', 'Status']);
            $this->tableSkeleton($contentX + 270, $y + 150, 110, 110, ['Announcements']);
        } elseif ($screen === 'Employees') {
            $this->formLine($contentX, $y + 82, 160, 'Search employees');
            $this->formLine($contentX + 172, $y + 82, 100, 'Department');
            $this->tableSkeleton($contentX, $y + 122, 375, 145, ['Employee', 'Department', 'Designation', 'Status']);
        } else {
            $this->roundedRect($contentX, $y + 82, 125, 155, 6, [255, 255, 255], [226, 232, 240]);
            $this->text($contentX + 12, $y + 105, 'Request Leave', 'Helvetica-Bold', 9, [30, 41, 59]);
            $this->formLine($contentX + 12, $y + 125, 98, 'Leave Type');
            $this->formLine($contentX + 12, $y + 160, 98, 'Dates');
            $this->roundedRect($contentX + 12, $y + 195, 98, 28, 4, [255, 255, 255], [226, 232, 240]);
            $this->tableSkeleton($contentX + 145, $y + 82, 235, 155, ['Employee', 'Type', 'Status', 'Action']);
        }
    }

    private function formLine(float $x, float $y, float $w, string $label): void
    {
        $this->text($x, $y, $label, 'Helvetica', 6.5, [71, 85, 105]);
        $this->roundedRect($x, $y + 7, $w, 18, 3, [255, 255, 255], [203, 213, 225]);
    }

    private function tableSkeleton(float $x, float $y, float $w, float $h, array $headers): void
    {
        $this->roundedRect($x, $y, $w, $h, 6, [255, 255, 255], [226, 232, 240]);
        $colW = $w / count($headers);
        foreach ($headers as $i => $header) {
            $this->text($x + 10 + $i * $colW, $y + 22, $header, 'Helvetica-Bold', 6.5, [71, 85, 105]);
        }
        for ($r = 1; $r <= 4; $r++) {
            $yy = $y + 25 + $r * 20;
            $this->setStroke(241, 245, 249);
            $this->line($x + 10, $yy, $x + $w - 10, $yy);
            for ($c = 0; $c < count($headers); $c++) {
                $this->rect($x + 12 + $c * $colW, $yy + 8, min(42, $colW - 18), 4, [226, 232, 240], [226, 232, 240]);
            }
        }
    }

    public function rect(float $x, float $y, float $w, float $h, array $fill, array $stroke = null): void
    {
        $py = self::HEIGHT - $y - $h;
        $this->setFill(...$fill);
        if ($stroke) {
            $this->setStroke(...$stroke);
            $op = 'B';
        } else {
            $op = 'f';
        }
        $this->raw(sprintf("%.2F %.2F %.2F %.2F re %s\n", $x, $py, $w, $h, $op));
    }

    public function roundedRect(float $x, float $y, float $w, float $h, float $r, array $fill, array $stroke): void
    {
        // A clean rectangular fallback keeps the generator compact while preserving visual structure.
        $this->rect($x, $y, $w, $h, $fill, $stroke);
    }

    public function line(float $x1, float $y1, float $x2, float $y2): void
    {
        $this->raw(sprintf("%.2F %.2F m %.2F %.2F l S\n", $x1, self::HEIGHT - $y1, $x2, self::HEIGHT - $y2));
    }

    public function arrow(float $x1, float $y1, float $x2, float $y2): void
    {
        $this->setStroke(100, 116, 139);
        $this->line($x1, $y1, $x2, $y2);
        $this->line($x2, $y2, $x2 - 5, $y2 - 4);
        $this->line($x2, $y2, $x2 - 5, $y2 + 4);
    }

    public function text(float $x, float $y, string $text, string $font = 'Helvetica', float $size = 10, array $color = [0, 0, 0]): void
    {
        $fontMap = [
            'Helvetica' => 'F1',
            'Helvetica-Bold' => 'F2',
            'Times-Roman' => 'F3',
            'Courier' => 'F4',
            'Helvetica-Oblique' => 'F5',
        ];

        $this->setFill(...$color);
        $escaped = $this->escape($this->clean($text));
        $this->raw("BT /" . ($fontMap[$font] ?? 'F1') . " {$size} Tf {$x} " . (self::HEIGHT - $y) . " Td ({$escaped}) Tj ET\n");
    }

    public function setFill(int $r, int $g, int $b): void
    {
        $this->raw(sprintf("%.3F %.3F %.3F rg\n", $r / 255, $g / 255, $b / 255));
    }

    public function setStroke(int $r, int $g, int $b): void
    {
        $this->raw(sprintf("%.3F %.3F %.3F RG\n", $r / 255, $g / 255, $b / 255));
    }

    private function raw(string $command): void
    {
        $this->pages[$this->pageIndex] .= $command;
    }

    private function wrap(string $text, float $width, float $size): array
    {
        $max = max(24, (int) floor($width / ($size * 0.48)));
        $words = preg_split('/\s+/', trim($this->clean($text))) ?: [];
        $lines = [];
        $line = '';

        foreach ($words as $word) {
            if (strlen($line . ' ' . $word) > $max && $line !== '') {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = trim($line . ' ' . $word);
            }
        }

        if ($line !== '') {
            $lines[] = $line;
        }

        return $lines;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private function clean(string $text): string
    {
        $text = str_replace(["\r", "\t"], ['', '    '], $text);
        $text = str_replace(['₹', '–', '—', '’', '“', '”', '•'], ['INR ', '-', '-', "'", '"', '"', '-'], $text);
        return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $text) ?? $text;
    }

    private function clip(string $text, int $length): string
    {
        return strlen($text) > $length ? substr($text, 0, $length - 3) . '...' : $text;
    }
}

function section(AcademicPdf $pdf, string $heading, string $body, float $y = 100): float
{
    $pdf->title($heading, $y);
    return $pdf->paragraph($body, 48, $y + 46, 500);
}

$outDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'reports';
if (! is_dir($outDir)) {
    mkdir($outDir, 0775, true);
}

$pdf = new AcademicPdf();

// 1. Title page
$pdf->addPage();
$pdf->rect(0, 0, 595.28, 841.89, [248, 250, 252], [248, 250, 252]);
$pdf->rect(0, 0, 595.28, 112, [15, 23, 42], [15, 23, 42]);
$pdf->text(48, 62, 'PeopleOps HRMS', 'Helvetica-Bold', 30, [255, 255, 255]);
$pdf->text(50, 89, 'Academic Software Engineering Report', 'Helvetica', 13, [203, 213, 225]);
$pdf->roundedRect(48, 168, 500, 230, 10, [255, 255, 255], [226, 232, 240]);
$pdf->text(76, 214, 'Human Resource Management System', 'Helvetica-Bold', 22, [15, 23, 42]);
$pdf->text(76, 246, 'Laravel, Supabase PostgreSQL, Render, and Tailwind CSS', 'Helvetica', 12, [71, 85, 105]);
$pdf->text(76, 290, 'Prepared as a structured project report covering architecture, database design,', 'Times-Roman', 11, [51, 65, 85]);
$pdf->text(76, 308, 'implementation, deployment, workflows, results, and future improvements.', 'Times-Roman', 11, [51, 65, 85]);
$pdf->roundedRect(76, 340, 110, 36, 5, [79, 113, 255], [79, 113, 255]);
$pdf->text(99, 363, 'PO HRMS', 'Helvetica-Bold', 13, [255, 255, 255]);
$pdf->text(48, 710, 'Project: PeopleOps HRMS', 'Helvetica', 10, [51, 65, 85]);
$pdf->text(48, 730, 'Generated: May 2026', 'Helvetica', 10, [51, 65, 85]);
$pdf->text(48, 750, 'Environment: Laravel 10 application deployed on Render with Supabase PostgreSQL', 'Helvetica', 10, [51, 65, 85]);

// 2. Abstract
$pdf->addPage();
$y = section($pdf, 'Abstract', 'PeopleOps HRMS is a web-based human resource management system designed to centralize employee records, role-based access control, departments, attendance, leave approvals, payroll processing, and internal announcements. The software replaces fragmented manual HR operations with an integrated workflow built on Laravel and PostgreSQL. The system follows a modular MVC design, uses authenticated sessions, applies permission-based middleware, and exposes administrative screens for HR teams and managers. This report documents the design and implementation of the software as an academic case study, including requirements, architecture, database design, backend logic, deployment practices, screenshots, outcomes, challenges, and future scope.');
$pdf->subtitle('Keywords', 48, $y + 28);
$pdf->paragraph('HRMS, Laravel, PostgreSQL, Supabase, Render, role-based access control, payroll, attendance, leave management, MVC architecture.', 48, $y + 50, 500);

// 3. Table of contents
$pdf->addPage();
$pdf->title('Table of Contents');
$toc = [
    '1. Introduction',
    '2. Problem Statement',
    '3. Existing Problems and Literature Review',
    '4. System Architecture',
    '5. Database Design',
    '6. Backend and API Design',
    '7. Technologies Used',
    '8. Implementation',
    '9. Screenshots and Workflow',
    '10. Results and Outcomes',
    '11. Challenges and Limitations',
    '12. Future Improvements',
    '13. Conclusion',
];
$y = 150;
foreach ($toc as $i => $item) {
    $pdf->text(80, $y, $item, 'Helvetica', 12, [30, 41, 59]);
    $pdf->text(500, $y, (string) ($i + 4), 'Helvetica', 12, [100, 116, 139]);
    $y += 34;
}

// 4. Introduction
$pdf->addPage();
$y = section($pdf, '1. Introduction', 'Human resource management is a core administrative function in every organization. It involves recruitment records, departmental assignment, attendance tracking, leave management, payroll processing, and internal communication. When these activities are maintained in disconnected spreadsheets, paper registers, and informal messages, HR teams face slow reporting, inconsistent data, and reduced accountability. PeopleOps HRMS addresses this gap by offering a centralized digital platform for operational HR workflows.');
$y = $pdf->paragraph('The application provides a login-protected workspace where authorized users can manage employees, departments, attendance records, leave requests, payroll entries, announcements, and roles. The design emphasizes a clean dashboard, searchable records, consistent forms, and permissions that can be customized by role. The implementation is suitable for small to mid-sized organizations that need a practical HRMS without excessive setup complexity.', 48, $y + 16, 500);
$pdf->subtitle('Project Objectives', 48, $y + 28);
$pdf->bulletList([
    'Create a centralized HRMS application for employee and people operations data.',
    'Provide role and permission controls for HR, payroll, employee, and administrative functions.',
    'Track attendance, leave requests, payroll status, departments, designations, and announcements.',
    'Deploy the application using Docker on Render while storing production data in Supabase PostgreSQL.',
], 60, $y + 54, 465);

// 5. Problem statement
$pdf->addPage();
$y = section($pdf, '2. Problem Statement', 'Small teams often begin HR operations with spreadsheets and manual approvals. As the organization grows, the same approach becomes fragile: employee records are duplicated, leave requests are lost, salary calculations become hard to audit, and managers cannot view reliable real-time information. The primary problem is the lack of a unified, permission-aware, and web-accessible HR platform.');
$pdf->subtitle('Core Problems Identified', 48, $y + 28);
$pdf->bulletList([
    'Employee master data is difficult to maintain consistently across HR, finance, and management teams.',
    'Attendance and leave data are often disconnected, making absence reporting slow and error-prone.',
    'Payroll entries need traceable status management and department-level employee context.',
    'Administrative access must be restricted so users only see and modify the workflows relevant to their roles.',
    'Deployment should be simple enough for cloud hosting while supporting a reliable managed database.',
], 60, $y + 54, 465);

// 6. Literature review
$pdf->addPage();
$y = section($pdf, '3. Existing Problems and Literature Review', 'Traditional HR information systems have evolved from record-keeping systems into integrated human capital platforms. Common HRMS literature emphasizes data centralization, self-service, role-based authorization, workflow automation, and reporting accuracy. Modern web frameworks enable these features at lower cost by combining MVC architecture, relational databases, reusable UI components, and cloud deployment.');
$pdf->subtitle('Observed Gaps in Lightweight HR Systems', 48, $y + 28);
$pdf->bulletList([
    'Spreadsheet systems are flexible but weak in validation, concurrency, history, and authorization.',
    'Generic project templates provide authentication but usually lack HR-specific data models.',
    'Many payroll and attendance tools are isolated products and do not share a common employee profile.',
    'Cloud deployment commonly fails when environment variables, storage permissions, HTTPS proxy settings, or database connectivity are not configured correctly.',
], 60, $y + 54, 465);

// 7. System architecture
$pdf->addPage();
$pdf->title('4. System Architecture');
$pdf->paragraph('The system follows a layered architecture. Users interact with responsive Blade templates styled with Tailwind CSS and compiled by Vite. Requests pass through Render, Nginx, PHP-FPM, Laravel routes, authentication middleware, and permission middleware before reaching controllers. Controllers coordinate models and database operations. Supabase provides managed PostgreSQL storage using a transaction pooler suitable for IPv4 hosting environments such as Render.', 48, 140, 500);
$pdf->architectureDiagram(58, 245);
$pdf->figureCaption('Figure 1. High-level deployment and request architecture.', 58, 440);

// 8. MVC architecture
$pdf->addPage();
$pdf->title('4.1 MVC and Request Flow');
$pdf->paragraph('Laravel implements the Model-View-Controller pattern. Routes map URLs to controller methods, controllers validate and process user actions, Eloquent models represent tables, and Blade views render user interfaces. Middleware adds cross-cutting behavior such as authentication, CSRF protection, and permission checks.', 48, 140, 500);
$steps = [
    ['Browser form submits POST /login', 'CSRF token and session cookie are sent.'],
    ['Route resolves controller action', 'web.php maps login and HRMS routes.'],
    ['Middleware validates access', 'auth and permission middleware protect modules.'],
    ['Controller performs business logic', 'Attendance, leave, payroll, and employee controllers update models.'],
    ['Model persists data', 'Eloquent writes to PostgreSQL through pgsql connection.'],
    ['Blade renders response', 'Views return the next screen or redirect.'],
];
$y = 220;
foreach ($steps as $i => [$a, $b]) {
    $pdf->roundedRect(70, $y, 455, 46, 5, [248, 250, 252], [226, 232, 240]);
    $pdf->roundedRect(86, $y + 10, 25, 25, 4, [79, 113, 255], [79, 113, 255]);
    $pdf->text(95, $y + 27, (string) ($i + 1), 'Helvetica-Bold', 10, [255, 255, 255]);
    $pdf->text(128, $y + 20, $a, 'Helvetica-Bold', 10, [15, 23, 42]);
    $pdf->text(128, $y + 36, $b, 'Helvetica', 8.5, [71, 85, 105]);
    $y += 58;
}

// 9. Database design
$pdf->addPage();
$pdf->title('5. Database Design');
$pdf->paragraph('The database is normalized around a users table and HR-specific tables. Roles store permission slugs as JSON. Employee profiles connect users to departments and designations. Attendance records store daily clock and status information. Leave requests connect users to leave types and approval metadata. Payroll records store pay period, gross components, deductions, tax, net pay, and payment status.', 48, 140, 500);
$pdf->erDiagram(48, 230);
$pdf->figureCaption('Figure 2. Simplified entity relationship diagram for the HRMS schema.', 48, 570);

// 10. Database table summary
$pdf->addPage();
$pdf->title('5.1 Table Summary');
$tables = [
    ['users', 'Authentication identity, employee code, account status, contact details, and role assignment.'],
    ['roles', 'Readable role names, slugs, descriptions, status, and JSON permission lists.'],
    ['departments', 'Organization units with codes, descriptions, status, and optional manager references.'],
    ['designations', 'Job titles grouped by department and grade.'],
    ['employee_profiles', 'Employment metadata including manager, location, salary, address, and bank details.'],
    ['attendance_records', 'Daily work records with clock-in, clock-out, status, and notes.'],
    ['leave_requests', 'Employee leave periods, type, reason, status, approval, and rejection notes.'],
    ['payrolls', 'Pay period records for basic salary, allowances, deductions, tax, net pay, and payment status.'],
    ['announcements', 'Company-wide or department-specific communication items.'],
];
$y = 145;
foreach ($tables as [$name, $desc]) {
    $pdf->roundedRect(55, $y, 485, 42, 5, [255, 255, 255], [226, 232, 240]);
    $pdf->text(70, $y + 18, $name, 'Courier', 9, [79, 113, 255]);
    $pdf->text(185, $y + 18, $desc, 'Times-Roman', 8.9, [51, 65, 85]);
    $y += 52;
}

// 11. Backend/API design
$pdf->addPage();
$pdf->title('6. Backend and API Design');
$pdf->paragraph('The application is primarily server-rendered using Laravel routes and Blade views. It does not depend on a separate SPA API for normal operation. Backend design focuses on route groups, named routes, controller methods, request validation, Eloquent relationships, and middleware. The web guard handles authentication, and custom permission middleware checks role permissions before allowing module access.', 48, 140, 500);
$pdf->codeBox(<<<'CODE'
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/employees', [EmployeeController::class, 'index'])
        ->middleware('permission:employees.view')
        ->name('employees.index');
});
CODE, 58, 260, 480, 160);
$pdf->paragraph('The route naming convention makes links stable across views. Permissions are attached to routes rather than hidden only in the UI, which prevents unauthorized users from accessing protected actions by typing URLs manually.', 58, 455, 470);

// 12. Permission design
$pdf->addPage();
$pdf->title('6.1 Role and Permission Logic');
$pdf->paragraph('The permission system converts traditional short role codes into readable permission slugs such as dashboard.view, employees.manage, leave.manage, payroll.view, and roles.manage. This makes authorization easier to audit and extend. Each user belongs to a role. The role stores a list of permissions, and middleware checks the current route requirement against the authenticated user.', 48, 140, 500);
$pdf->codeBox(<<<'CODE'
public function hasPermission(string $permission): bool
{
    if (! $this->role || ! $this->role->status) {
        return false;
    }

    return in_array($permission, $this->role->permissions ?? [], true);
}
CODE, 58, 260, 480, 160);
$pdf->bulletList([
    'Super Admin receives broad access to all HRMS modules.',
    'HR Manager can manage people records, departments, attendance, leave, announcements, and roles.',
    'Payroll Specialist can view and process salary records without unnecessary HR administration access.',
    'Employee access is limited to self-service workflows such as attendance, leave, announcements, and payslips.',
], 65, 455, 455);

// 13. Technologies used
$pdf->addPage();
$pdf->title('7. Technologies Used');
$tech = [
    ['Laravel 10', 'PHP framework used for routing, MVC structure, sessions, middleware, Blade templates, Eloquent ORM, and migrations.'],
    ['PostgreSQL / Supabase', 'Managed relational database used for production storage through the Supabase transaction pooler.'],
    ['Render', 'Docker-based hosting platform used for deploying the web service.'],
    ['Nginx and PHP-FPM', 'Container runtime stack that serves public assets and executes Laravel requests.'],
    ['Tailwind CSS and Vite', 'Frontend styling and asset build pipeline for responsive UI.'],
    ['Docker', 'Defines a reproducible deployment image with PHP extensions, Node build, Composer dependencies, and startup script.'],
];
$y = 142;
foreach ($tech as [$name, $desc]) {
    $pdf->roundedRect(55, $y, 485, 58, 6, [248, 250, 252], [226, 232, 240]);
    $pdf->text(72, $y + 22, $name, 'Helvetica-Bold', 11, [15, 23, 42]);
    $pdf->paragraph($desc, 190, $y + 18, 330, 8.8, 12.5);
    $y += 72;
}

// 14. Implementation
$pdf->addPage();
$pdf->title('8. Implementation');
$pdf->paragraph('Implementation began by converting the original Laravel application into a dedicated HRMS. New models, controllers, views, migrations, seeders, and middleware were introduced. The database was rebuilt around HRMS concepts. The user interface was redesigned to open directly into a login and dashboard workflow rather than the default Laravel welcome page. Demo data was seeded to provide realistic departments, employees, attendance, leave, payroll, and announcement records.', 48, 140, 500);
$pdf->subtitle('Implemented Modules', 48, 230);
$pdf->bulletList([
    'Dashboard with employee, department, attendance, and leave summaries.',
    'Employee master records with department, designation, role, status, and joining date.',
    'Department and designation management.',
    'Attendance list, clock-in/clock-out actions, status updates, and notes.',
    'Leave request submission, approval, rejection, and status filtering.',
    'Payroll entry and register with processed/paid statuses.',
    'Announcements and role-permission administration.',
], 60, 258, 460);

// 15. Deployment implementation
$pdf->addPage();
$pdf->title('8.1 Deployment Implementation');
$pdf->paragraph('Deployment required configuring the Docker image for PHP-FPM, Nginx, Composer, Node, and required PHP extensions. Because the production database uses Supabase PostgreSQL, the pdo_pgsql extension is included. The startup script prepares Laravel writable folders, clears and caches configuration, and starts PHP-FPM with Nginx. Runtime logs are sent to stderr, which is the expected logging channel for Render.', 48, 140, 500);
$pdf->codeBox(<<<'CODE'
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    gd \
    bcmath

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi
CODE, 58, 255, 480, 180);
$pdf->paragraph('A key deployment lesson was that Supabase direct database hosts may not be IPv4 compatible. Render should use the Supabase transaction pooler, typically on port 6543, with DB_SSLMODE=require.', 58, 470, 470);

// 16. Screenshots login/dashboard
$pdf->addPage();
$pdf->title('9. Screenshots and Workflow');
$pdf->paragraph('The following figures summarize the uploaded screenshots and user workflow. The PDF uses clean vector reproductions of the screens so that the figures remain readable in print.', 48, 135, 500);
$pdf->uiMockup('Login', 55, 200, 485, 260);
$pdf->figureCaption('Figure 3. Login screen workflow based on the uploaded login screenshot.', 55, 480);
$pdf->uiMockup('Dashboard', 55, 520, 485, 250);
$pdf->figureCaption('Figure 4. Dashboard workflow with summary cards, recent employees, announcements, and pending leave.', 55, 790);

// 17. Screenshots employees/leave
$pdf->addPage();
$pdf->title('9.1 Employee and Leave Workflows');
$pdf->uiMockup('Employees', 55, 145, 485, 260);
$pdf->figureCaption('Figure 5. Employee list, search, filters, status badges, and edit actions.', 55, 425);
$pdf->uiMockup('Leave', 55, 465, 485, 260);
$pdf->figureCaption('Figure 6. Leave request and approval workflow.', 55, 745);

// 18. Workflow narrative
$pdf->addPage();
$pdf->title('9.2 Workflow Narrative');
$pdf->subtitle('Authentication and Access', 48, 145);
$y = $pdf->paragraph('Users begin at the login screen and authenticate through Laravel sessions. After successful login, they are redirected to the dashboard. Role permissions determine which navigation items and routes are usable.', 48, 170, 500);
$pdf->subtitle('Operational HR Flow', 48, $y + 20);
$y = $pdf->bulletList([
    'HR creates departments and designations before assigning employees.',
    'Employee records are added with role, department, designation, salary, manager, and status details.',
    'Attendance is recorded daily, with status updates for present, absent, late, and leave cases.',
    'Employees or HR users submit leave requests; authorized users approve or reject pending requests.',
    'Payroll entries are generated for a pay period, processed, and then marked as paid.',
    'Announcements are published to all employees or scoped to a department.',
], 60, $y + 48, 460);
$pdf->subtitle('Administrative Flow', 48, $y + 20);
$pdf->paragraph('Administrators maintain role permission lists through the Roles and Permissions screen. The approach supports evolving access rules without hardcoding every role into controller logic.', 48, $y + 48, 500);

// 19. Results
$pdf->addPage();
$pdf->title('10. Results and Outcomes');
$pdf->paragraph('The completed application provides a working HRMS prototype with realistic seeded data and production deployment support. The main outcome is a coherent HR workflow that integrates employee records, organizational structure, attendance, leave, payroll, announcements, and access control into one Laravel application.', 48, 140, 500);
$pdf->subtitle('Measured Seed Data Outcome', 48, 220);
$pdf->bulletList([
    '12 users with roles and employee profiles.',
    '6 roles with readable permission slugs.',
    '5 departments and 16 designations.',
    '60 attendance records for realistic daily reporting.',
    '4 leave types and 4 leave requests covering pending, approved, and rejected states.',
    '24 payroll rows across April and May 2026.',
    '3 announcements and 4 asset records.',
], 60, 250, 460);
$pdf->subtitle('Functional Outcomes', 48, 430);
$pdf->paragraph('The application now opens to a branded login page, shows a fully styled dashboard, supports permission-protected HR modules, and can be deployed with Render and Supabase. The UI is appropriate for repeated administrative work rather than a marketing-style landing page.', 48, 455, 500);

// 20. Challenges
$pdf->addPage();
$pdf->title('11. Challenges and Limitations');
$pdf->bulletList([
    'Database migration: the original MySQL/MariaDB dump needed to be replaced with Laravel migrations and Supabase-compatible PostgreSQL schema creation.',
    'PostgreSQL driver: local PHP and the Docker image required pdo_pgsql to connect to Supabase.',
    'Render networking: Supabase direct hosts may resolve to IPv6, so Render requires the transaction pooler for reliable IPv4 access.',
    'Storage permissions: Laravel storage and cache directories must be writable at container runtime.',
    'HTTPS proxy behavior: Render terminates HTTPS before the container, so Laravel must trust forwarded proxy headers and force secure URLs.',
    'Current scope: the system is a strong operational prototype but does not yet include biometric attendance, PDF payslips, audit logs, or advanced reporting.',
], 60, 150, 460);

// 21. Future improvements
$pdf->addPage();
$pdf->title('12. Future Improvements');
$pdf->bulletList([
    'Add PDF payslip generation and employee payslip download history.',
    'Introduce audit logs for all administrative changes such as role edits, payroll updates, and leave approvals.',
    'Add dashboard charts for attendance trends, salary cost, department headcount, and leave utilization.',
    'Add email notifications for leave status, payroll processing, and announcements.',
    'Create manager self-service workflows for team-level approvals and attendance review.',
    'Add data export options for CSV, Excel, and month-end payroll reports.',
    'Improve mobile layouts and add progressive web app behavior for employee self-service.',
    'Add automated CI checks for migrations, feature tests, build output, and Docker image startup.',
], 60, 150, 460);

// 22. Conclusion
$pdf->addPage();
$y = section($pdf, '13. Conclusion', 'PeopleOps HRMS demonstrates how a Laravel application can be transformed into a complete human resource management platform. The system provides practical modules for employees, departments, attendance, leave, payroll, announcements, and roles. Its normalized database design, permission middleware, Blade-based interface, and cloud deployment configuration make it suitable as a foundation for a production HRMS.');
$y = $pdf->paragraph('The project also highlights important deployment lessons: managed PostgreSQL requires the correct driver, container storage must be writable, logs should go to stderr on cloud platforms, and HTTPS proxy handling must be configured explicitly. With future improvements such as audit trails, analytics, notifications, and payslip exports, the system can evolve from a working prototype into a richer HR operations product.', 48, $y + 16, 500);
$pdf->subtitle('Final Statement', 48, $y + 52);
$pdf->paragraph('The developed software meets its core objective: it turns fragmented HR activities into a structured, role-aware, database-backed web application that can be accessed securely from a cloud deployment.', 48, $y + 78, 500);

$target = $outDir . DIRECTORY_SEPARATOR . 'PeopleOps_HRMS_Academic_Report.pdf';
$pdf->save($target);

echo $target . PHP_EOL;
