
    <div class="container">
        <h1>Certificates</h1>
        <a href="{{ route('certificates.create') }}" class="btn btn-primary">Create Certificate</a>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-2">
                {{ $message }}
            </div>
        @endif

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($certificates as $certificate)
                    <tr>
                        <td>{{ $certificate->id }}</td>
                        <td>{{ $certificate->title }}</td>
                        <td>
                            <a href="{{ route('certificates.edit', $certificate->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
