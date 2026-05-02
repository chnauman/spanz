@extends('emails.layout')

@section('title', 'New Tenders Matching Your Interests - Spanz')

@section('content')
<h2>Hello{{ $userName ? ' ' . $userName : '' }}!</h2>

@if($frequency === 'daily')
    <p>Here are the new tenders posted today that match your interests:</p>
@elseif($frequency === 'weekly')
    <p>Here are the new tenders posted this week that match your interests:</p>
@else
    <p>Here are the new tenders posted this month that match your interests:</p>
@endif

@if($tenders->count() > 0)
    <div style="margin: 24px 0;">
        <table style="width: 100%; border-collapse: collapse; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background-color: #f7fafc;">
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #1a202c; font-weight: 600; font-size: 14px;">Title</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #1a202c; font-weight: 600; font-size: 14px;">Category</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #1a202c; font-weight: 600; font-size: 14px;">Budget</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #1a202c; font-weight: 600; font-size: 14px;">Deadline</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #1a202c; font-weight: 600; font-size: 14px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenders as $tender)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; color: #2d3748; font-size: 14px;">
                        <strong>{{ $tender->cardTitle() }}</strong>
                    </td>
                    <td style="padding: 12px; color: #4a5568; font-size: 14px;">
                        {{ $tender->category->name ?? 'N/A' }}
                    </td>
                    <td style="padding: 12px; color: #4a5568; font-size: 14px;">
                        @if($tender->budget)
                            {{ $tender->currency }} {{ number_format($tender->budget, 2) }}
                        @else
                            Not specified
                        @endif
                    </td>
                    <td style="padding: 12px; color: #4a5568; font-size: 14px;">
                        {{ $tender->getFormattedDeadline('d M Y') }}
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('tenders.detail', $tender->id) }}" 
                           style="display: inline-block; background-color: #0D6AED; color: #ffffff !important; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: 500;">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p style="color: #718096; font-size: 14px; padding: 16px; background-color: #f7fafc; border-radius: 8px;">
        No new tenders matching your interests were found for this period.
    </p>
@endif

<p style="margin-top: 24px;">You can manage your notification preferences in your <a href="{{ route('account.profile') }}" style="color: #0D6AED; text-decoration: underline;">account settings</a>.</p>

<p style="margin-top: 28px;">Best regards,<br>
<strong style="color: #1a202c;">Spanz Team</strong></p>
@endsection

