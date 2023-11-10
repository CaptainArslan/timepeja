@component('mail::message')
# Organization Deactivation Code

Hello {{ $organization->name }},

We have received a request to deactivate your account. To complete this process, please use the following information:

- **Email Address:** {{ $organization->email }}
- **Deactivation Code:** {{ $organization->deactivate_code }}

Please keep this information confidential. If you did not request this deactivation, please contact our support team immediately.

Thank you for using our services.

Best regards,

Stoppick Team
03-111-111-111

Thanks,<br>
{{ config('app.name') }}
@endcomponent