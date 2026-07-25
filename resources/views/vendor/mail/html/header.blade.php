@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<table cellpadding="0" cellspacing="0" role="presentation" style="display:inline-table;vertical-align:middle;">
<tr>
<td style="vertical-align:middle;padding-right:10px;">
<span style="display:inline-block;width:32px;height:32px;border-radius:8px;background:#6366f1;color:#ffffff;font-weight:800;font-size:15px;text-align:center;line-height:32px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;">{{ Str::substr(config('app.name'), 0, 1) }}</span>
</td>
<td style="vertical-align:middle;">
<span style="font-size:17px;font-weight:800;letter-spacing:-.02em;color:#18181b;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;">{{ config('app.name') }}</span>
</td>
</tr>
</table>
</a>
</td>
</tr>
