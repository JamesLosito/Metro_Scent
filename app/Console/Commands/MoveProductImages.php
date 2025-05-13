<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class MoveProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:move-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move product images from public to storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to move product images...');

        // Create storage directories if they don't exist
        Storage::disk('public')->makeDirectory('products/captivating');
        Storage::disk('public')->makeDirectory('products/intense');

        // Move captivating images
        $captivatingPath = public_path('images/captivating');
        if (File::exists($captivatingPath)) {
            $files = File::files($captivatingPath);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $newPath = 'products/captivating/' . $filename;
                
                // Copy file to storage
                Storage::disk('public')->put($newPath, File::get($file));
                
                // Update product in database
                $product = Product::where('name', pathinfo($filename, PATHINFO_FILENAME))->first();
                if ($product) {
                    $product->update([
                        'image' => $newPath,
                        'type' => 'captivating'
                    ]);
                }
                
                $this->info("Moved captivating image: {$filename}");
            }
        }

        // Move intense images
        $intensePath = public_path('images/intense');
        if (File::exists($intensePath)) {
            $files = File::files($intensePath);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $newPath = 'products/intense/' . $filename;
                
                // Copy file to storage
                Storage::disk('public')->put($newPath, File::get($file));
                
                // Update product in database
                $product = Product::where('name', pathinfo($filename, PATHINFO_FILENAME))->first();
                if ($product) {
                    $product->update([
                        'image' => $newPath,
                        'type' => 'intense'
                    ]);
                }
                
                $this->info("Moved intense image: {$filename}");
            }
        }

        $this->info('Product images have been moved successfully!');
    }
}
