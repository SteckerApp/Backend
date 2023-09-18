@component('mail::message')
# Invitation Mail

<!-- Hi {{ $name }}, -->

You have been invited to join stecker as an .

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
