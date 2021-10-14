<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTemplatesTable extends Migration
{
    private string $table = 'templates';

    public function up()
    {
        Schema::table(
            $this->table,
            function (Blueprint $table) {
                $table->boolean('is_default')->default(true);
            }
        );
    }

    public function down()
    {
        Schema::table(
            $this->table,
            function (Blueprint $table) {
                $table->dropColumn('is_default');
            }
        );
    }
}
