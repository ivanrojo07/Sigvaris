<ul class="nav nav-pills nav-fill">
    <li role="presentation" class="nav-item"><a href="{{ route('pacientes.historial', ['paciente' => $paciente]) }}" id="nav-historial" class="nav-link submenu">Historial:</a></li>
    <li role="presentation" class="nav-item"><a href="{{ route('pacientes.tallas.index', ['paciente' => $paciente]) }}" id="nav-tallas" class="nav-link submenu">Medidas:</a></li>
    <li role="presentation" class="nav-item"><a href="{{ route('getCrmsPorCliente', ['paciente' => $paciente]) }}" id="nav-crm" class="nav-link submenu">CRM:</a></li>
    <li role="presentation" class="nav-item"><a href="{{ route('pacientes.tutores.index', ['paciente' => $paciente]) }}" id="nav-tutor" class="nav-link submenu">Padre o Tutor:</a></li>
    <li role="presentation" class="nav-item"><a href="{{ route('pacientes.venta', ['paciente' => $paciente]) }}" id="nav-venta" class="nav-link submenu">Punto de venta:</a></li>
</ul>