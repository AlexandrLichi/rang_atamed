<?php
use Pet\Migration\Migration;
use Pet\Migration\Schema;
use Pet\Migration\Table;
            
class users_rewiews_settings extends Migration{

    public function up(){

    Schema::create("user", function(Table $table){

                $table->id();
                $table->timestamp();
                $table->string('name');
                $table->string('email');
                $table->string('password');
                $table->integer('company_id')->index()->foreign('company');
                $table->string('auth');
                $table->boolean('active')->default(true);
                $table->string('job');

        });

        Schema::create("company", function (Table $table) {

                    $table->id();
                    $table->timestamp();
                    $table->string('company');
                    $table->string('email');
                    $table->string('api');
                    $table->text('sample-client')->null();
                    $table->boolean('active')->default(true);
        
        });

        Schema::create("filial", function (Table $table) {

                        $table->id();
                        $table->timestamp();
                        $table->string('filial');
                        $table->string('address')->null();
                        $table->string('api');
                        $table->boolean('active')->default(true);
                        $table->integer('company_id')->index()->foreign('company');
                        $table->string('platform_marketing')->null();
                        $table->string('imap')->null();
        });

        Schema::create("parsers-list", function (Table $table) 
        {
            $table->id();
            $table->timestamp();
            $table->string('name', 150);
            $table->string('query', 1000)->null();
        });

        Schema::create("parsers", function (Table $table) {

                        $table->id();
                        $table->timestamp();
                        $table->integer('parser_id')->index()->foreign('parser-list');
                        $table->string('filial');
                        $table->string('api');
                        $table->integer('filial_id')->index()->foreign('filial');
                        $table->text('setting')->null();

        });

        Schema::create( "client",
            function (Table $table) {
                $table->id();
                $table->timestamp();
                $table->string('company');
                $table->string('name');
                $table->string('filial');
                $table->string('api');
                $table->string('telefon');
                $table->string('canal')->null();
                $table->string('status_canal')->null();
                $table->string('date_path')->null();
                $table->string('path')->null();
                $table->string('url');
                $table->string('platform_marketing');
                $table->string('platform_path')->null();
                $table->integer('like_star')->default('0');
                $table->string('text_negative')->null();
                $table->string('requestId')->null();
                
            });
        Schema::create("analytics", function (Table $table) {

            $table->id();
            $table->timestamp();
            $table->string('api');
            $table->string('filial', 200);
            $table->string('star', 10);
            $table->string('platform', 200);
            $table->string('count', 10);
            $table->string('real_count');
          
        });
   }


  public function registerTableCompany($api){

        Schema::create($api, function (Table $table) {
            $table->id();
            $table->timestamp();
            $table->string('contrib', 500);
            $table->string('name', 150);
            $table->string('text', 1300)->null();
            $table->string('api');
            $table->string('filial');
            $table->date();
            $table->string('answer', 1300)->null();
            $table->string('star', 10);
            $table->date('dateanswer')->null();
            $table->string('platform', 150);
            $table->string('img', 300)->null();
            $table->string('dalete_mild')->null();
            $table->string('hash', 1000);
        });

  }



    function back(){
        Schema::drop("user");
        Schema::drop("parsers");
        Schema::drop("filial");
        Schema::drop("company");
    }
}
?>