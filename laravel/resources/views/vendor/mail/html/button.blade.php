<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td style="display: flex; align-items: center; justify-content: center; vertical-align: middle;">
    @if(!isset($image))
        <img src="{{ config('resource.url'). '/images/fi/splash_bottom.png'}}" alt="fi" width="40%">
    @endif
    <a href="{{ $url }}" class="button btn button-{{ $color ?? 'primary' }}" style="height: 40px; margin-top: 10%" target="_blank" rel="noopener">{{ $slot }}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
