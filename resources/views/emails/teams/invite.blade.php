@component('mail::message')
# Invitation Mail

Hi {{ $name }}, 

You have been invited to join stecker as {{ $invite->role }} by {{$company}}.
@component('mail::button', ['url' => $url])
Accept Invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
