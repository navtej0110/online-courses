<footer>
    <div class="container">
        <p>&copy; Your Website 2019. All Rights Reserved.</p>
        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="#">Privacy</a>
            </li>
            <li class="list-inline-item">
                <a href="#">Terms</a>
            </li>
            <li class="list-inline-item">
                <a href="#">FAQ</a>
            </li>
        </ul>
    </div>
</footer>

<!-- Bootstrap core JavaScript -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="{{ url('/front/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ url('/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<!-- Plugin JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/easytimer@1.1.1/dist/easytimer.min.js"></script>

<!-- Custom scripts for this template -->
<script src="{{ url('/front/js/new-age.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.js" integrity="sha256-NSuqgY2hCZJUN6hDMFfdxvkexI7+iLxXQbL540RQ/c4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>
@yield('footer-js')

<script type="text/javascript">
    $(function () {
        $('.selectpicker').selectpicker();
    });
    $('.selectpicker').on('change', function (e) {
        var val = $(this).val();
        //var url = "{{ route('language-switcher', ['locale' => "+val+"]) }}";
        var url = "/setlocale/" + val;
        window.location.href = url;
    });
</script>