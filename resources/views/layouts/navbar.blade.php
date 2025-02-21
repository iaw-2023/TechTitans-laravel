<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/home">
      <img src="{{ asset('/images/logo.png') }}" alt="Logo" style="max-width: 40px;">
      Gestion de los datos
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link" href="/turnos">Turnos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/canchas">Canchas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/categorias">Categorias</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/reservas">Reservas</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <span id="weather-widget" class="nav-link">
            Cargando clima...
          </span>
        </li>
        <li class="nav-item dropdown">
          <a class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" 
             aria-expanded="false">
            {{ Auth::user()->refresh()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-1" aria-labelledby="bd-theme">
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                {{ ('Profile') }}
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('logout') }}" 
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ ('Log out') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  function updateWeatherWidget() {
    $.ajax({
      url: '/get-weather',
      method: 'GET',
      success: function(data) {
        // Aquí actualizamos el widget con los datos del clima
        $('#weather-widget').html(`${data.weather[0].description}: ${data.main.temp}°C | ${data.localTime}`);
      },
      error: function() {
        $('#weather-widget').html('No se pudo obtener el clima');
      }
    });
  }

  // Llamamos a la función para actualizar el clima al cargar la página
  updateWeatherWidget();
</script>

