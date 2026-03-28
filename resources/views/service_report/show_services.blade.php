<table class="table table-sm">
    <thead>
    <tr>
        <th scope="col">Datum</th>
        <th scope="col">Stunden</th>
        <th scope="col">gefahrene Kilometer</th>
    </tr>
    </thead>
    <tbody>
        @foreach($serviceReport->services as $service)
            <tr>
                <th scope="row">{{ $service->provided_on }}</th>
                <td>{{ Number::format($service->hours) }}</td>
                <td>{{ Number::format($service->kilometres) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
