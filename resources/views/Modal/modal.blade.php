<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="background-color:#212529">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle" style="color:#ffffff;">Información de la cancha</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="color:#ffffff;">
        <div>
          <h6>Nombre y ID:</h6>
          <p>{{ $turno->cancha->nombre}} ID:{{ $turno->id_cancha}}</p>
        </div>
        <div>
          <h6>Esta activa:</h6>
          <p>{{ $turno->cancha->activo  ? 'Si' : 'No' }}</p>
        </div>
        <div>
          <h6>Reservada:</h6>
          <p>si o no</p>
        </div>
        <div>
          <h6>Categoria:</h6>
          <p>{{ $turno->cancha->categoria->nombre}}</p>
        </div>
        <div>
          <h6>Precio:</h6>
          <p>{{ $turno->cancha->precio}}</p>
        </div>
        <div>
          <h6>Cant. Jugadores:</h6>
          <p>{{ $turno->cancha->cant_jugadores}}</p>
        </div>
        <div>
          <h6>Superficie:</h6>
          <p>{{ $turno->cancha->superficie}}</p>
        </div>
        <div>
          <h6>Techo:</h6>
          <p>{{ $turno->cancha->techo ? 'Si' : 'No' }}</p>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>