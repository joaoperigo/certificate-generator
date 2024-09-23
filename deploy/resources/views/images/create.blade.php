<form action="{{ route('images.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="image-upload">Upload Image:</label>
    <input type="file" name="image" id="image-upload">
    <button type="submit">Upload</button>
</form>

<label for="bg-image">Select Background Image:</label>
<select id="bg-image" onchange="setImagePath(this)">
    @foreach($images as $image)
        <option value="{{ asset('storage/' . $image->file_path) }}">{{ $image->name }}</option>
    @endforeach
</select>
<input type="hidden" id="image-path-input">

<script>
    function setImagePath(select) {
        document.getElementById('image-path-input').value = select.value;
    }
</script>