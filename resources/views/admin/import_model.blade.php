

<form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".xlsx">
    <button type="submit">Import</button>
</form>