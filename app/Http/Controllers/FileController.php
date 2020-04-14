<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\ProductExport;
use Excel;
use UxWeb\SweetAlert\SweetAlert as Alert;
use App\Http\Requests\ImportExcelProductos;
use App\Services\Productos\StoreExcelProductosService;
//Excel
use App\Services\Pacientes\StoreExcelPacientesService;


class FileController extends Controller
{
    //
    public function __construct(StoreExcelProductosService $storeExcelProductosService)
    {
        $this->storeExcelProductosService = $storeExcelProductosService;
        $this->storeExcelPacientesService = new StoreExcelPacientesService([]);
    }

    public function importExportExcelORCSV()
    {
        return view('excel.importar');
    }

    public function importFileIntoDB(ImportExcelProductos $request)
    {
        $file = request()->file('sample_file');
        $this->storeExcelProductosService->make($file);
        //$this->storeExcelPacientesService->make($file);

        Alert::success('Archivo subido correctamente.');
        return redirect()->back();
    }

    public function downloadExcelFile($type)
    {
        if (!$type === "xlsx") {
            return Excel::download(new ProductExport, 'invoices.csv', \Maatwebsite\Excel\Excel::CSV);
        }
        return Excel::download(new ProductExport, 'productos.xlsx');
    }
}
