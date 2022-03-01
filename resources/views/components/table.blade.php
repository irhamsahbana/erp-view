@props([
    'thead' => []
])

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px;">#</th>
                @foreach($thead as $th)
                    <th>{{ $th }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>