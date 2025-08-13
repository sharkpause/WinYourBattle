<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
     public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

		Storage::extend('gcs_private', function ($app, $config) {
			return new class($config) {
			    protected $storage;
			    protected $bucket;
			
			    public function __construct($config) {
					$this->storage = new StorageClient([
					    'projectId' => $config['project_id'],
					    'keyFilePath' => $config['key_file'],
					]);
				
					$this->bucket = $this->storage->bucket($config['bucket']);
			    }
			
			    public function put($path, $contents) {
					$this->bucket->upload(
					    is_resource($contents) ? $contents : $this->streamContents($contents),
					    ['name' => $path] // NO ACL HERE
					);
					return true;
			    }
			
			    public function get($path) {
					return $this->bucket->object($path)->downloadAsString();
			    }
			
			    public function exists($path) {
					return $this->bucket->object($path)->exists();
			    }
			
			    public function delete($path) {
					return $this->bucket->object($path)->delete();
			    }
			
			    protected function streamContents($contents) {
					$stream = fopen('php://temp', 'r+');
					fwrite($stream, $contents);
					rewind($stream);
					return $stream;
			    }
			};
		});

		Storage::extend('gcs_public', function ($app, $config) {
			return new class($config) {
			    protected $storage;
			    protected $bucket;
			
			    public function __construct($config) {
					$this->storage = new StorageClient([
					    'projectId' => $config['project_id'],
					    'keyFilePath' => $config['key_file'],
					]);
				
					$this->bucket = $this->storage->bucket($config['bucket']);
			    }
			
			    public function put($path, $contents) {
					$this->bucket->upload(
					    is_resource($contents) ? $contents : $this->streamContents($contents),
					    ['name' => $path] // NO ACL HERE
					);
					return true;
			    }
			
			    public function get($path) {
					return $this->bucket->object($path)->downloadAsString();
			    }
			
			    public function exists($path) {
					return $this->bucket->object($path)->exists();
			    }
			
			    public function delete($path) {
					return $this->bucket->object($path)->delete();
			    }
			
			    protected function streamContents($contents) {
					$stream = fopen('php://temp', 'r+');
					fwrite($stream, $contents);
					rewind($stream);
					return $stream;
			    }
			};
		});
    }
}
