@component('mail::message')
# Account Deactivation

Dear {{ $organization->name }},

We regret to inform you that your account with {{ config('app.name') }} has been deactivated.

**Account Details:**

- **Username:** {{ $organization->name }}
- **Email:** {{ $organization->email }}
- **Account Code:** {{ $organization->code }}

If you have any concerns or would like to discuss this further, please don't hesitate to reach out to our support team.

We appreciate your time with us and are here to assist you with any questions you may have.

Sincerely,

Stoppick Team:
03-111-111-111

Thanks,<br>
{{ config('app.name') }}
@endcomponent