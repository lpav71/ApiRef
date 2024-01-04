<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Providers\ImportExport;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected ImportExport $importExport;
    protected Client $clientModel;

    /**
     * @param Client $clientModel
     * @param ImportExport $importExport
     */
    public function __construct(Client $clientModel, ImportExport $importExport)
    {
        $this->clientModel = $clientModel;
        $this->importExport = $importExport;
        $this->middleware('auth:api');
    }

    public function export(Request $request): string
    {
        $collection = $request->collection;
        $headTable = $request->header;
        return $this->importExport->runExport($headTable, $collection);
    }

    public function exportClients(Request $request): string
    {
        $club_id = $request->club_id;
        $users = $this->clientModel->clients2($club_id);
        return $this->importExport->runExportClients($users);
    }

    public function importClients(Request $request)
    {
        $fileName = $request->filename;
        $file = fopen($fileName, 'r'); // Открываем файл для чтения
        $ids = [];
        $clientsFromFile = [];
        $this->importExport->readCSVFile($file,$clientsFromFile, $ids);
        $clients = $this->clientModel->getAllClientsByIds($ids);
        $this->importExport->insertNewRecords($clientsFromFile, $clients);
        fclose($file); // Закрываем файл
    }
}
