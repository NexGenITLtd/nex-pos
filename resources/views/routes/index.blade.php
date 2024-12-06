<table>
    <thead>
        <tr>
            <th>Method</th>
            <th>URI</th>
            <th>Name</th>
            <th>Controller</th>
            <th>Middleware</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($routes as $route)
            <tr>
                <td>{{ implode('|', $route->methods()) }}</td>
                <td>{{ $route->uri() }}</td>
                <td>{{ $route->getName() ?? 'N/A' }}</td>
                <td>{{ $route->getActionName() ?? 'Closure' }}</td>
                <td>{{ implode(', ', $route->middleware()) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
