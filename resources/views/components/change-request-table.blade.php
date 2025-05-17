@props(['requests'])

<div class="container">
    <h1>Permintaan Perubahan Data</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($requests->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Requester</th>
                <th>Model</th>
                <th>Action</th>
                <th>Data (Edit)</th>
                <th>Status</th>
                <th>Approve/Reject</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td>{{ $req->id }}</td>
                    <td>{{ $req->requester->name }}</td>
                    <td>{{ $req->model_type }} #{{ $req->model_id }}</td>
                    <td>{{ ucfirst($req->action) }}</td>
                    <td>
                        @if($req->action === 'edit')
                            <pre>{{ json_encode($req->data, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ ucfirst($req->status) }}</td>
                    <td>
                        @if($req->status === 'pending')
                        <form action="{{ route('changeRequests.approve', $req->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-success btn-sm" onclick="return confirm('Setujui permintaan ini?')">Approve</button>
                        </form>
                        <form action="{{ route('changeRequests.reject', $req->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak permintaan ini?')">Reject</button>
                        </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $requests->links() }}
    @else
        <p>Tidak ada permintaan perubahan.</p>
    @endif
</div>
