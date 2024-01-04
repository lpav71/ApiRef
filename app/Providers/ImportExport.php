<?php

namespace App\Providers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class ImportExport extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImportExport::class, function ($app) {
            return new ImportExport($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * @param mixed $headTable
     * @param mixed $collection
     * @return string
     */
    public function runExport(mixed $headTable, mixed $collection): string
    {
        $h = explode(',', $headTable);
        $date = date('Y-m-d H:i:s');
        $fileName = md5($date) . ".csv";
        header("Content-Type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$fileName");

        $file = fopen("CSV/$fileName", 'w');

        // Устанавливаем кодировку для записи в файл
        fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM

        fputcsv($file, $h, ';');

        // Записываем данные из базы данных
        foreach ($collection as $item) {
            fputcsv($file, (array)$item, ';');
        }

        fclose($file);
        return $fileName;
    }

    /**
     * @param \Illuminate\Support\Collection $users
     * @return string
     */
    public function runExportClients(\Illuminate\Support\Collection $users): string
    {
        $date = date('Y-m-d H:i:s');
        $fileName = md5($date) . ".csv";
        header("Content-Type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$fileName");

        $file = fopen("CSV/$fileName", 'w');

        // Устанавливаем кодировку для записи в файл
        //fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM

        // Записываем данные из базы данных
        foreach ($users as $user) {
            fputcsv($file, $user->toArray(), ';');
        }

        fclose($file);
        return $fileName;
    }
    /**
     * @param bool $file
     * @param array $clientsFromFile
     * @param array $ids
     * @return array
     */
    public function readCSVFile(mixed $file, array &$clientsFromFile, array &$ids): array
    {
        while (($line = fgetcsv($file, 0, ';')) !== false) {
            $clientsFromFile[] = $line;
            $ids[] = (int)$line[0];
        }
        return array($clientsFromFile, $ids);
    }

    /**
     * @param array $clientsFromFile
     * @param mixed $clients
     * @return void
     */
    public function insertNewRecords(array $clientsFromFile, mixed $clients): void
    {
        foreach ($clientsFromFile as $clientFromFile) {
            if (!$clients->contains($clientFromFile[0])) {
                // Создание нового клиента на основе данных из $clientFromFile
                $group_create = $clientFromFile[24] ?? null;
                if ($group_create != '') {
                    $group_create = Carbon::parse($clientFromFile[24]);
                }
                $client = new Client;
                $client->club_id = $clientFromFile[1];
                $client->login = $clientFromFile[2];
                $client->password = $clientFromFile[3];
                $client->phone = $clientFromFile[4];
                $client->email = $clientFromFile[5];
                $client->icon = $clientFromFile[6];
                $client->amount = (double)$clientFromFile[7];
                $client->bonus = (double)$clientFromFile[8];
                $client->total_time = $clientFromFile[9] != '' ? $clientFromFile[9] : 0;
                $client->full_name = $clientFromFile[10];
                $client->status_active = $clientFromFile[11] == 1;
                $client->telegram_id = $clientFromFile[12];
                $client->vk_id = $clientFromFile[13];
                $client->reg_date = $clientFromFile[14] == '' ? null : Carbon::parse($clientFromFile[14]);
                $client->bday = $clientFromFile[15] == '' ? null : Carbon::parse($clientFromFile[15]);
                $client->verify = $clientFromFile[16] == 1;
                $client->verify_dt = $clientFromFile[17] == '' ? null : Carbon::parse($clientFromFile[17]);
                $client->name = $clientFromFile[18];
                $client->surname = $clientFromFile[19];
                $client->middle_name = $clientFromFile[20];
                $client->group_id = $clientFromFile[21];
                $client->group_amount = $clientFromFile[22];
                $client->group_create = $group_create;
                $client->save();
            }
        }
    }
}
