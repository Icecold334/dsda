<?php

namespace App\Providers;

use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\URL;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('gcs', function ($app, $config) {
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $config['key_file']);

            $client = new StorageClient([
                'projectId'   => $config['project_id'],
                'keyFilePath' => $config['key_file'],
            ]);

            $bucket = $client->bucket($config['bucket']);
            $adapter = new GoogleCloudStorageAdapter(
                $bucket,
                $config['path_prefix'] ?? null
            );

            $filesystem = new Filesystem($adapter);

            // 💡 Tambahkan custom URL handler di sini
            return new class($filesystem, $adapter, $config) extends FilesystemAdapter {
                public function url($path)
                {
                    $prefix = $this->config['path_prefix'] ?? '';
                    $bucketName = $this->config['bucket'];
                    $fullPath = trim("{$prefix}/{$path}", '/');

                    // 🔥 Bangun URL publik GCS
                    return "https://storage.googleapis.com/{$bucketName}/{$fullPath}";
                }
            };
        });
    }
}
