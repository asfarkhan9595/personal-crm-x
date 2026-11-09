<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Portfolio Contact Message</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 20px; color: #333; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background: #4f46e5; color: #ffffff; padding: 20px 24px; }
        .header h2 { margin: 0; font-size: 20px; }
        .content { padding: 24px; }
        .field { margin-bottom: 16px; }
        .label { font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: 600; margin-bottom: 4px; display: block; }
        .value { font-size: 15px; color: #0f172a; line-height: 1.5; }
        .message-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; font-size: 14px; color: #334155; white-space: pre-wrap; }
        .footer { padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>🔔 New Portfolio Contact Message</h2>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">From</span>
                <div class="value"><strong>{{ $contactMessage->name }}</strong> (&lt;{{ $contactMessage->email }}&gt;)</div>
            </div>
            <div class="field">
                <span class="label">Subject</span>
                <div class="value">{{ $contactMessage->subject ?: 'N/A' }}</div>
            </div>
            <div class="field">
                <span class="label">Date Received</span>
                <div class="value">{{ $contactMessage->created_at ? $contactMessage->created_at->format('d M Y, h:i A') : 'Just now' }}</div>
            </div>
            <div class="field">
                <span class="label">Message</span>
                <div class="message-box">{{ $contactMessage->message }}</div>
            </div>
        </div>
        <div class="footer">
            <a href="{{ route('admin.contact-messages.index') }}" class="btn">View in Admin Panel</a>
        </div>
    </div>
</body>
</html>

