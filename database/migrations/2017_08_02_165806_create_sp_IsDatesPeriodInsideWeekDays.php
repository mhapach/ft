<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpIsDatesPeriodInsideWeekDays extends Migration
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
            -- Create date: 02-08-2017
            -- Description:	Проверяем входит ли период в дни недели
            -- =============================================
            CREATE FUNCTION [SBSN].[IsDatesPeriodInsideWeekDays] 
            (
                @dBeginDate DATETIME,
                @dEndDate DATETIME,
                @sWeek VARCHAR(7)
            )
            RETURNS INT
            AS
            BEGIN
              DECLARE @sPeriodWeekDays VARCHAR(7), @nRes INT; 
              SET @sPeriodWeekDays = REPLACE(dbo.GetWeekDays(@dBeginDate, @dEndDate), '%', ''); 
            
              SET @nRes = CHARINDEX ( @sPeriodWeekDays, @sWeek );
              RETURN CASE WHEN @nRes > 0 THEN 1 ELSE 0 END;	 
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
        DB::statement("DROP FUNCTION SBSN.IsDatesPeriodInsideWeekDays");
    }
}
