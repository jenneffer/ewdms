Hello <i>{{ $demo->receiver }}</i>,
<p>This is an inquiry from {{$demo->senderName}}</p>
 
<p><u>Inquiry Details:</u></p>
 
<div>
<p><b>Title:</b>&nbsp;{{ $demo->title }}</p>
<p><b>Content:</b>&nbsp;{{ $demo->content }}</p>
@if(!empty($demo->attachment))
<p>Please see attachment for your reference.</p>
@endif
</div>
 
Thank You,
<br/>
<i>{{ $demo->sender }}</i>