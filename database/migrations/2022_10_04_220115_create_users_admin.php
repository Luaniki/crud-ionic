<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users_admin', function (Blueprint $table) {
            $table->id();
            $table->string('names', 250);
            $table->string('username', 50)->unique();
            $table->text('password');
            $table->string('rol', 50);
            $table->timestamps();
        });

        DB::table('users_admin')->insert([
            'names' => 'ROOT',
            'username' => 'root',
            'password' => 'root',
            'rol' => 'admin',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_admin');
    }
};
