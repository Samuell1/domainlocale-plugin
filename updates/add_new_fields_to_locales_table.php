<?php namespace Samuell\DomainLocale\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddNewFieldsToLocalesTable extends Migration
{
    public function up()
    {
        Schema::table('rainlab_translate_locales', function($table)
        {
            $table->string('domain')->nullable();
        });
    }
    public function down()
    {
        Schema::table('rainlab_translate_locales', function($table)
        {
            $table->dropColumn(['domain']);
        });
    }
}
