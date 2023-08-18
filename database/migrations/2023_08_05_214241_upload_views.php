<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sqlFilePath = 'database/views/query.sql';

        if (File::exists($sqlFilePath)) {
            $sql = File::get($sqlFilePath);
            DB::unprepared($sql);
        } else {
            throw new \Exception ('SQL file not found: ' . $sqlFilePath);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
