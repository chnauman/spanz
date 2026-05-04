<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents shared</title>
</head>
<body style="font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; line-height: 1.5; color: #13283f; max-width: 560px; margin: 0 auto; padding: 24px;">
    <p>Hi {{ $recipient->name }},</p>

    <p>
        <strong>{{ $share->sender?->companyDetail?->company_name ?? $share->sender?->name }}</strong>
        has shared {{ $share->files->count() }} document(s) with you on SPANZ.
    </p>

    @if(filled($share->message))
        <p style="white-space: pre-wrap; background: #f4f7fb; padding: 12px 16px; border-radius: 8px;">{{ $share->message }}</p>
    @endif

    <p><strong>Files:</strong></p>
    <ul>
        @foreach($share->files as $f)
            <li>{{ $f->original_name }}</li>
        @endforeach
    </ul>

    <p>
        <a href="{{ $dashboardUrl }}" style="display: inline-block; background: #0d6aed; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 600;">Open received documents</a>
    </p>

    <p style="font-size: 14px; color: #64748b;">You can also reach this page from your supplier dashboard sidebar: <em>Received documents</em>.</p>

    <p style="font-size: 14px; color: #64748b;">— SPANZ</p>
</body>
</html>
