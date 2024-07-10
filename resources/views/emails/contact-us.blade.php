@component('mail::message')
# Contact Form Submission

**Name:** {{ $data['name'] }}
**Phone:** {{ $data['phone'] }}
**Email:** {{ $data['email'] }}

**Message:**
{{ $data['message'] }}
@endcomponent