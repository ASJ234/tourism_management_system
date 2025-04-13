<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class BookingPdfService
{
    public function generateBookingPdf(Booking $booking)
    {
        // Generate HTML content
        $html = View::make('pdf.booking', [
            'booking' => $booking
        ])->render();

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'booking_');
        file_put_contents($tempFile . '.html', $html);

        // Convert HTML to PDF using wkhtmltopdf
        $pdfFile = $tempFile . '.pdf';
        $wkhtmltopdf = '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"';
        $command = sprintf(
            '%s --enable-local-file-access %s %s',
            $wkhtmltopdf,
            escapeshellarg($tempFile . '.html'),
            escapeshellarg($pdfFile)
        );
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Failed to generate PDF: ' . implode("\n", $output));
        }

        // Read the PDF content
        $pdfContent = file_get_contents($pdfFile);

        // Clean up temporary files
        unlink($tempFile . '.html');
        unlink($pdfFile);

        return $pdfContent;
    }
} 