@extends("emails.base")
@section("content")

<div>
<h3>Requesting a Demo</h3>

Fullname : {{$full_name}} <br>
Mobile Number : {{$mobile_no}} <br>
Email Address: {{$email}} <br>
How did you hear about us : {{$hear_about_us}} <br>

</div>
@endsection
