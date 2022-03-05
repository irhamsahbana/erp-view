@if (session('f-msg'))
    <input type="hidden" id="f-msg"  value="{{ session('f-msg') }}" disabled>

    <script>
        $(document).ready(function() {
            let msg = $("#f-msg").val();

            $(document).Toasts('create', {
                class: 'bg-success',
                title: '',
                body: msg
            })
        });
    </script>
@endif

@if ($errors->any())

    <div class="error_msgs">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        $(document).ready(function() {
            let msg = $('.error_msgs').clone();
            $('.error_msgs').hide();

            $(document).Toasts('create', {
                class: 'bg-danger',
                title: '',
                body: msg
            })
        });
    </script>
@endif