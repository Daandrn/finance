@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <script>
                alert('{{ $error }}')
            </script>
        @endforeach
    </div>
@endif