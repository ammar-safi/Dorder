<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FixTrans extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = $this->getAvailableModels();
        foreach ($models as $model) {
            $use = new $model;
            $table_name = $use->getTable();
            $products = DB::table($table_name)->get();
            foreach ($products as $key => $product) {
                $product = $use::find($product->id);
                $columns = $product->getTransAble();
                $ignore = ['id', 'image'];
                foreach ($columns as $column) {
                    if (!in_array($column, $ignore))
                        $product
                            ->setTranslation($column, 'en', $products[$key]->$column)
                            ->save();
                }
            }

        }


    }
    public static function getAvailableModels()
    {

        $models = [];
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);
        foreach ($modelFiles as $modelFile) {
            $models[] = '\App\Models\\' . $modelFile->getFilenameWithoutExtension();
        }

        return $models;
    }
}
