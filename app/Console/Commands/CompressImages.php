<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CompressImages extends Command
{
    protected $signature = 'compress:images';
    protected $description = 'Compress and resize images';

//     public function handle()
//     {
//         // Specify the base directory containing your property folders
//         $baseDirectory = storage_path('app/public/properties');

//         // Ensure the base directory exists
//         if (!file_exists($baseDirectory)) {
//             $this->error('Base directory not found.');
//             return;
//         }

//         // Get all property folders in the specified base directory
//         $propertyFolders = File::directories($baseDirectory);

//         // Process each property folder
//         foreach ($propertyFolders as $propertyFolder) {
//             $this->info('Processing property folder: ' . $propertyFolder);

//             // Get the photos directory inside the property folder
//             $photosDirectory = $propertyFolder . '/photos';

//             // Skip processing if the photos directory doesn't exist
//             if (!file_exists($photosDirectory)) {
//                 $this->warn('Photos directory not found for property folder: ' . $propertyFolder);
//                 continue;
//             }

//             // Get all files in the specified photos directory
//             $files = File::allFiles($photosDirectory);

//             // Process images in chunks to avoid memory issues
//             $chunkSize = 100; // Adjust the chunk size based on your server's capabilities

//             // Chunk the files and process each chunk
//             foreach (array_chunk($files, $chunkSize) as $chunk) {
//                 foreach ($chunk as $file) {
//                     $this->info('Processing: ' . $file->getPathname());

//                     $image = Image::make($file->getPathname());

//                     // Resize and fit within the specified dimensions
//                     $image->fit(1000, 667);

//                     // Get the original filename and extension
//                     $originalFilename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

//                     // Overwrite the original image with the resized one
//                     $image->save($photosDirectory . '/' . $originalFilename . '.jpg', 80, 'jpg');

//                     $this->info('Processed: ' . $originalFilename . '.jpg');
//                 }
//             }

//             $this->info('Images compressed and replaced for property folder: ' . $propertyFolder);
//         }

//         $this->info('All property folders processed.');
//     }
// }


public function handle()
{
    // Specify the base directory containing your property folders
    $baseDirectory = storage_path('app/public/properties');

    // Ensure the base directory exists
    if (!file_exists($baseDirectory)) {
        $this->error('Base directory not found.');
        return;
    }

    // Define the start and end folder numbers
    $startFolder = 656;
    $endFolder = 1002;

    // Process property folders within the specified range
    for ($i = $startFolder; $i <= $endFolder; $i++) {
        $propertyFolder = $baseDirectory . '/' . $i;

        // Check if the property folder exists
        if (!file_exists($propertyFolder)) {
            $this->warn('Property folder not found: ' . $propertyFolder);
            continue;
        }

        $this->info('Processing property folder: ' . $propertyFolder);

        // Get the photos directory inside the property folder
        $photosDirectory = $propertyFolder . '/photos';

        // Skip processing if the photos directory doesn't exist
        if (!file_exists($photosDirectory)) {
            $this->warn('Photos directory not found for property folder: ' . $propertyFolder);
            continue;
        }

        // Get all files in the specified photos directory
        $files = File::allFiles($photosDirectory);

        // Process images in chunks to avoid memory issues
        $chunkSize = 100; // Adjust the chunk size based on your server's capabilities

        // Chunk the files and process each chunk
        foreach (array_chunk($files, $chunkSize) as $chunk) {
            foreach ($chunk as $file) {
                $this->info('Processing: ' . $file->getPathname());

                $image = Image::make($file->getPathname());

                // Resize and fit within the specified dimensions
                $image->fit(1000, 667);

                // Get the original filename and extension
                $originalFilename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

                // Overwrite the original image with the resized one
                $image->save($photosDirectory . '/' . $originalFilename . '.jpg', 100, 'jpg');

                $this->info('Processed: ' . $originalFilename . '.jpg');
            }
        }

        $this->info('Images compressed and replaced for property folder: ' . $propertyFolder);
    }

    $this->info('All property folders processed within the specified range.');
}
}