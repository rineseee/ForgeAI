<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')->where('name', 'admin')->update(['name' => 'owner']);
    }

    public function down(): void
    {
        DB::table('roles')->where('name', 'owner')->update(['name' => 'admin']);
    }
};
