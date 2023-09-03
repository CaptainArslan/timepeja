@component('mail::message')

Dear {{ $details['name'] }},

We are delighted to inform you that your registration with our platform has been successful. As requested, we have embedded an OTP (One-Time Password) as an extra layer of security for your account.

Your account details are as follows:

Username: {{ $details['name'] }}

Email: {{ $details['email'] }}

Phone Number: {{ $details['phone'] }}

OTP: {{ $details['otp'] }}

Please keep your OTP secure and do not share it with anyone. You will need it to log in to your account and to perform certain actions on our platform. In case you did not receive an OTP, please contact our customer support team as soon as possible.

We hope that you will find our platform useful and that it will meet your expectations. If you have any questions or concerns, please do not hesitate to contact us. Our team will be more than happy to assist you.

Thank you for choosing our platform.

Best regards,

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent