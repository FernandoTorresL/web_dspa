<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class XMLUploadController extends Controller
{
    public function showXMLUploadForm()
    {
        Log::info('Mostrar view UPLOAD:');
        return view('validate_xml.upload');
    }

    public function convertXmlToCsv(Request $request)
    {
        Log::info('Iniciando función convertXmlToCsv:');
        //$request->validate([
        //    'file' => 'required|mimes:xml|max:2048',
        //]);
        Log::info('Después de validación de archivo');

        $xmlFile = $request->file('xml_file');
        Log::info('Leer XML:');

        $csvFileName = 'converted_' . time() . '.csv';
        Log::info('Crear template csvFile:' . $csvFileName);

        $csvFilePath = 'csv/' . $csvFileName;
        Log::info('Crear csvFilePath:' . $csvFilePath);

        // Move uploaded XML file to storage/csv directory
        Log::info('Listo a guardar XML');
        $xmlFile->storeAs('csv', $csvFileName);
        Log::info('Store XML:' . $xmlFile);
        Log::info('Store CSV:' . $csvFileName);

        // Call python script to convert XML to CSV
        Log::info('Ruta Python:' . public_path('script/xml_to_csv.py'));
        $command = "python " . public_path('script'.DIRECTORY_SEPARATOR.'xml_to_csv.py') . " " . storage_path('app'.DIRECTORY_SEPARATOR.'csv'.DIRECTORY_SEPARATOR.$csvFileName) . " " . public_path('storage'.DIRECTORY_SEPARATOR.'csv'.DIRECTORY_SEPARATOR.$csvFileName);
//                                                                                                                                                                                C:\Users\fernando.torresl\Documents\Proyectos\FTL\IMSS\dd_web_dspa\public\storage
        //$command = "python xml_to_csv.py test.xml salida33.csv";
        Log::info('Command:'.$command);

        shell_exec($command);

        //$archivo->store('solicitudes/' . $user->delegacion_id, 'public'),

        // Return the CSV file path to blade template
        Log::info('Mover/Guardar csv:' . 'public/csv/' . $csvFileName);
        //$csvFileName->store('csv', 'public');
        Log::info('GUARDADO');

        return view('validate_xml.upload', ['csv_file' => 'public/csv/' . $csvFileName]);
    }
}
