<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmation - #{{ $booking->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .booking-details {
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .price-breakdown {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .price-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .price-total {
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Booking Confirmation</h1>
        <p>Booking ID: #{{ $booking->id }}</p>
    </div>

    <div class="booking-details">
        <div class="section">
            <div class="section-title">Package Information</div>
            <div class="info-grid">
                <div>Package Name: {{ $booking->package->name }}</div>
                <div>Destination: {{ $booking->package->destination->name }}</div>
                <div>Start Date: {{ $booking->start_date->format('M d, Y') }}</div>
                <div>Duration: {{ $booking->package->duration_days }} Days</div>
                <div>Number of Travelers: {{ $booking->number_of_travelers }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="info-grid">
                <div>Name: {{ $booking->user->full_name }}</div>
                <div>Email: {{ $booking->user->email }}</div>
                <div>Phone: {{ $booking->user->phone_number ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Payment Information</div>
            <div class="info-grid">
                <div>Payment Status: {{ $booking->payment_status }}</div>
                <div>Booking Status: {{ $booking->status }}</div>
            </div>
            
            <div class="price-breakdown">
                <div class="price-item">
                    <span>Package Price (per person): ${{ number_format($booking->package->price, 2) }}</span>
                </div>
                <div class="price-item">
                    <span>Number of Travelers: {{ $booking->number_of_travelers }}</span>
                </div>
                <div class="price-item price-total">
                    <span>Total Amount: ${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        @if($booking->special_requests)
        <div class="section">
            <div class="section-title">Special Requests</div>
            <p>{{ $booking->special_requests }}</p>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This is your booking confirmation document. Please keep this document for your records.</p>
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html> 