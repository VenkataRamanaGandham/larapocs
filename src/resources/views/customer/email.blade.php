@component('mail::message')
# Introduction

New Query from ::{{ $firstname }} 
<br/>
Query::
{{ $lastname }}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
