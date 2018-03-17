<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcWrapperGetNewKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            -- =============================================
            -- Author:		Mohamed
            -- Create date: 03-08-2017
            -- Description:	возвращает новый id в таблице
            -- =============================================        
            CREATE PROCEDURE SBSN.wrapper_GetNewKey
              @strKeyTable varchar(100) = null
            AS
            BEGIN
                SET NOCOUNT ON;
                DECLARE @KeyTable varchar(100), @nLastKey INT 
                
                EXEC GetNewKey @strKeyTable = 'clients', @nLastKey = @nLastKey OUTPUT
                SELECT @nLastKey AS id
            END         
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP PROCEDURE SBSN.wrapper_GetNewKey");
    }
}
