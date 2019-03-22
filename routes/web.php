<?php

Route::resource('proveedores','Proveedor\ProveedorController');
// Route::resource('proveedores.direccionFiscal','Proveedor\ProveedorDireccionFiscalController');
Route::resource('proveedores.direccionfisica','Proveedor\ProveedorDireccionFisicaController');
Route::resource('proveedores.datosgenerales','Proveedor\ProveedorDatosGeneralesController');
Route::resource('proveedores.contacto','Proveedor\ProveedorContactoController');
Route::resource('proveedores.datosbancarios','Proveedor\ProveedorDatosBancariosController');

Route::resource('doctores','Doctor\DoctorController');
Route::resource('doctores.consultorios','Doctor\DoctorConsultorioController');
Route::resource('doctores.especialidades','Doctor\DoctorEspecialidadController');
Route::resource('doctores.premios','Doctor\DoctorPremioController');
Route::get('doctores.pacientes/{doctor}','Doctor\DoctorPacienteController@getPacientes')->name('doctor.pacientes');

Route::resource('empleados','Empleado\EmpleadoController');
Route::resource('empleados.datoslaborales','Empleado\EmpleadosDatosLabController');
Route::resource('empleados.estudios','Empleado\EmpleadosEstudiosController');
Route::resource('empleados.emergencias','Empleado\EmpleadosEmergenciasController');
Route::resource('empleados.vacaciones','Empleado\EmpleadosVacacionesController');
Route::resource('empleados.faltas','Empleado\EmpleadosFaltasAdministrativasController');
Route::get('getfaltas','Falta\FaltaController@getFaltas');
Route::resource('faltas','Falta\FaltaController', ['except'=>'show']);
Route::resource('niveles', 'Nivel\NivelController');

Route::resource('pacientes', 'Paciente\PacienteController');
Route::resource('pacientes.tallas', 'Paciente\PacienteTallaController');
Route::resource('crm', 'Paciente\PacienteCrmController');
Route::get('pacientes/{paciente}/crm', 'Paciente\PacienteCrmController@getCrmCliente')->name('getCrmsPorCliente');
Route::resource('pacientes.tutores', 'Paciente\PacienteTutorController');
Route::get('getDoctores','Doctor\DoctorController@getDoctores');

Route::resource('contratos','Precargas\TipoContratoController');
Route::resource('descuentos', 'Venta\DescuentoController');
Route::resource('productos', 'Producto\ProductoController');
Route::get('import-export-csv-excel', array('as' => 'excel.import', 'uses' => 'FileController@importExportExcelORCSV'));
Route::post('import-csv-excel', array('as' => 'import-csv-excel', 'uses' => 'FileController@importFileIntoDB'));
Route::get('download-excel-file/{type}', array('as' => 'excel-file', 'uses' => 'FileController@downloadExcelFile'));

Route::get('pacientes/{paciente}/ventas/historial', 'Venta\VentaController@indexConPaciente')->name('pacientes.historial');
Route::get('pacientes/{paciente}/ventas', 'Venta\VentaController@createConPaciente')->name('pacientes.venta');
Route::resource('ventas', 'Venta\VentaController');

Route::resource('giros', 'Giro\GiroController', ['except' => 'show']);
Route::resource('areas','Area\AreaController', ['except'=>'show']);
Route::resource('puestos','Puesto\PuestoController', ['except'=>'show']);
Route::resource('bancos','Banco\BancoController', ['except'=>'show']);
Route::resource('bajas','Precargas\TipoBajaController');
// Route::resource('bajas','Precargas\TipoBajaController');
// Route::get('buscarempleado','Empleado\EmpleadoController@buscar');
// Route::resource('personals', 'Personal\PersonalController');
// Route::resource('personals.datoslaborales', 'Personal\PersonalDatosLabController');
// Route::resource('personals.referenciapersonales', 'Personal\PersonalRefPersonalController');
// Route::resource('personals.datosbeneficiario', 'Personal\PersonalBeneficiarioController');
// Route::resource('personals.producto','Personal\PersonalProductoController');
// Route::resource('personals.products.transactions', 'Personal\PersonalProductTransactionController',['only'=>'store']);
// Route::resource('personals.product','Personal\PersonalProductController', ['only'=>'index']);
Route::get('/', function () {
    return view('index');
});
