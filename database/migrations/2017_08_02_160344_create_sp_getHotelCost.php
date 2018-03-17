<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpGetHotelCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Не под силу созд\ьть эту процедуру из юникса на MSSQL - Freetds находит размномастные ошибки
        return 1;
        DB::statement("
            CREATE PROCEDURE SBSN.getHotelsCost
                @nCountryId INT,
                @nCityId INT = 0,
                @nResortId INT = 0,
                @nHotelId INT = 0,--360,
                @nRoomId INT = 2, --[1 SGL, 2- DBL , 3-TPL и тд]
                @nRoomCategoryId INT = 0, --71,
                @nAccmdId INT = 1, --[1-Adult, 2-Adult ExBed, 3 - CH привязанный к отелю]
                @nCode1 INT = 0,
                @nCode2 INT = 0, -- Питание
                @nPrKey INT = 0,
                @nPkKey INT = 0,
                @dBeginDate DATETIME,
                @dEndDate DATETIME,
                @nCurrencyId INT = 1,
                @nShowOnlyMinPrices BIT = 0,
                @nOffset INT = 0,
                @nLimit INT = 20
            AS
            BEGIN
                SET NOCOUNT ON;
            
                --Уменьшем кол-во дней потому что счтаем в ночах
                SET @dEndDate = DATEADD(DAY, -1, @dEndDate); 
                DECLARE @nSvKey int,
                        @sToCurrencyCode VARCHAR(3) ,
                        @dToday DATETIME,
                        @nDuration INT ,
                        @nMainPax INT,
                        @nMainRoomId INT;

                SELECT  @nSvKey = 3,
                        @sToCurrencyCode  = '$',
                        @dToday = GETDATE(),
                        @nDuration  = DATEDIFF(DAY, @dBeginDate, @dEndDate),
                        @nMainPax = CASE WHEN @nRoomId = 1 THEN 1 ELSE 2 END,
                        @nMainRoomId = CASE WHEN @nRoomId = 1 THEN 1 ELSE 2 END;
            
                IF OBJECT_ID ('tempdb..#tCosts', 'U') IS NOT NULL
                    DROP TABLE #tCosts;
            
                CREATE TABLE #tCosts (
                    nId INT PRIMARY KEY IDENTITY,
                    nCsId INT NOT NULL UNIQUE,
                    nCode INT NOT NULL,
                    nCode1 INT NOT NULL,
                    nCode2 INT NOT NULL,
                    nPrKey INT NOT NULL,
                    nPkKey INT NOT NULL,
                    dBeginDate DATETIME NOT NULL,
                    dEndDate DATETIME NOT NULL,
                    nCost float,
                    nIsPriceByDay BIT,
                    nRmKey INT NULL,
                    nRcKey INT NULL,
                    nAcKey INT NULL
                );
                CREATE UNIQUE INDEX IX_COMP ON #tCosts (nCode, nCode1, nCode2, nPrKey, nPkKey, dBeginDate) WITH IGNORE_DUP_KEY;
            
                -------------
                -- 1. Вычисляем стоимость размещения для основных мест (SGL Или DBL)
                -------------
            
                INSERT INTO #tCosts(
                    nCsId, nCode, nCode1, nCode2, nPrKey, nPkKey, dBeginDate, dEndDate, nCost, nIsPriceByDay, nRmKey, nRcKey, nAcKey
                )
                SELECT
                    CS_ID, CS_CODE, CS_SUBCODE1, CS_SUBCODE2, CS_PRKEY, CS_PKKEY,
                    CAST(CASE WHEN CS_DATE < @dBeginDate THEN @dBeginDate ELSE CS_DATE END as DATETIME) as dBeginDate,
                    CAST(CASE WHEN CS_DATEEND > @dEndDate THEN @dEndDate ELSE CS_DATEEND END as DATETIME) as dEndDate,
                    CS_COST, CS_BYDAY, HR_RMKEY, HR_RCKEY, HR_ACKEY
                FROM tbl_costs
                    INNER JOIN HotelDictionary  ON HD_KEY = CS_CODE
                        AND (@nCountryId = 0 OR HD_CNKEY = @nCountryId)
                        AND (@nCityId = 0 OR HD_CTKEY = @nCityId)
                        AND (@nResortId = 0 OR HD_RSKEY = @nResortId)
                        AND ISNULL(HD_WEB,0) = 1
                    INNER JOIN HotelRooms hr    ON hr.HR_KEY = CS_SUBCODE1
                        AND hr_RMKEY = @nMainRoomId
                        AND (@nRoomCategoryId = 0 OR hr_RCKEY = @nRoomCategoryId)
                        AND (@nAccmdId = 0 OR HR_ACKEY = @nAccmdId)
                WHERE
                    CS_SVKey = @nSvKey
                    AND ( @nHotelId = 0 OR CS_CODE = @nHotelId )
                    AND ( @nCode2 = 0 OR CS_SUBCODE2 = @nCode2 )
                    AND ( @nCode1 = 0 OR CS_SUBCODE1 = @nCode1 )
                    AND ( @dBeginDate between ISNULL(CS_CheckInDateBEG, '1970-01-01') AND ISNULL(CS_CheckInDateEnd, '2100-01-01') )
                    AND ( CS_DateEnd >= @dBeginDate AND CS_DATE <= @dEndDate )
                AND ( @nDuration BETWEEN ISNULL(CS_LONGMIN,0) AND ISNULL(CS_LONG, 1000) )
                ORDER BY
                    CS_CheckInDateBEG Desc, CS_CheckInDateEnd, dBeginDate,
                    --для дней недели все проживание обязательно дожно попадать в период
                    CASE WHEN ISNULL(CS_WEEK,'') != '' AND SBSN.IsDatesPeriodInsideWeekDays(@dBeginDate, @dEndDate, CS_WEEK) = 1 THEN 1 ELSE 0 END DESC,
                    CASE WHEN CS_DATE = @dBeginDate THEN ISNULL(CS_LONG, 0) ELSE ISNULL(CS_LONG, 10000) END DESC, CS_LONGMIN DESC,
                    -- такова логика МТ  //МТ считает так что если первой стоит цена на продолжительность то берем еёё
                    -- НО если следующий период с продолжительностью и другой без , то он считает что добирать надо с того что без
                    ISNULL(CS_DateSellEnd, '21000101') desc, ISNULL(CS_DateSellBeg, '19000101'), CS_BYDAY, CS_WEEK ASC;
            
                -- 1.1 В случае если мы вычисляем только наименьший прайс то вставляем записи в таблицу с уникальным ключем с игнорированием одинаковых записей
                IF( @nShowOnlyMinPrices = 1 ) BEGIN
                    IF OBJECT_ID ('tempdb..#tMinCosts', 'U') IS NOT NULL
                        DROP TABLE #tMinCosts;
            
                    CREATE TABLE #tMinCosts (
                        nId INT PRIMARY KEY IDENTITY,
                        nCsId INT NOT NULL UNIQUE,
                        nCode INT NOT NULL,
                        nCode1 INT NOT NULL,
                        nCode2 INT NOT NULL,
                        nPrKey INT NOT NULL,
                        nPkKey INT NOT NULL,
                        dBeginDate DATETIME NOT NULL,
                        dEndDate DATETIME NOT NULL,
                        nCost float,
                        nIsPriceByDay BIT,
                        nRmKey INT NULL,
                        nRcKey INT NULL,
                        nAcKey INT NULL
                    );
                    CREATE UNIQUE INDEX IX_COMP ON #tMinCosts(nCode) WITH IGNORE_DUP_KEY;
            
                    INSERT INTO #tMinCosts(
                      nCsId, nCode, nCode1, nCode2, nPrKey, nPkKey, dBeginDate, dEndDate, nCost, nIsPriceByDay,nRmKey,nRcKey,nAcKey
                    )
                    SELECT
                        nCsId, nCode, nCode1, nCode2, nPrKey, nPkKey, dBeginDate, dEndDate, nCost, nIsPriceByDay,nRmKey,nRcKey,nAcKey
                    FROM #tCosts;
            
                    TRUNCATE TABLE #tCosts;
                    INSERT INTO #tCosts(
                      nCsId, nCode, nCode1, nCode2, nPrKey, nPkKey, dBeginDate, dEndDate, nCost, nIsPriceByDay,nRmKey,nRcKey,nAcKey
                    )
                    SELECT
                        nCsId, nCode, nCode1, nCode2, nPrKey, nPkKey, dBeginDate, dEndDate, nCost, nIsPriceByDay,nRmKey,nRcKey,nAcKey
                    FROM #tMinCosts;
            
                    DROP TABLE #tMinCosts
                END;
            
                CREATE TABLE #tFinalTable(
                    nId INT PRIMARY KEY IDENTITY,
                    nPrice DECIMAL(19,2) NULL,
                    nTotalDays INT NULL,
                    nCode INT NULL,
                    nCode1 INT NULL,
                    nCode2 INT NULL,
                    nPrKey INT NULL,
                    nPkKey INT NULL,
                    nRmKey INT NULL,
                    nRcKey INT NULL,
                    nAcKey INT NULL,
                    nExtraBedCode1 INT NULL,
                    nExtraBedPrice INT NULL
                );
            
                INSERT INTO #tFinalTable(
                    nPrice,
                    nTotalDays,
                    nCode,
                    nCode1,
                    nCode2,
                    nPrKey,
                    nPkKey,
                    nRmKey,
                    nRcKey,
                    nAcKey
                )
                SELECT
                    CAST(SUM(nCost * @nMainPax * CASE WHEN nIsPriceByDay = 1 THEN DATEDIFF(DAY, dBeginDate, dEndDate)+1 ELSE 1 END) + .5 AS INT) as nPrice,
                    SUM(DATEDIFF(DAY, dBeginDate, dEndDate)+1) as nTotalDays,
                    nCode,
                    nCode1,
                    nCode2,
                    ISNULL(nPrKey, 0),
                    ISNULL(nPkKey, 0),
                    ISNULL(nRmKey, 0),
                    nRcKey,
                    nAcKey
                FROM #tCosts
                GROUP BY nCode, nCode1, nCode2, nPrKey, nPkKey, nRmKey, nRcKey, nAcKey
                HAVING SUM(DATEDIFF(DAY, dBeginDate, dEndDate)+1)>= @nDuration
                ORDER BY nPrice;
            
                ---------------------
                -- 2. В случае если был передан ExBed или Ch то вычисляем стоимость доп места
                -- Вычисляем стоиимости доп кроватей, когда перейдем на цены за номер это н понадобится
                --------------------- 
                DECLARE @nId INT, @nPrice DECIMAL(19,2), @nTotalDays INT, @nCode INT,  @nRmKey INT, @nRcKey INT, @nAcKey INT;
                DECLARE @nExBedPriceBrutto DECIMAL(19,2), @nAdultExBedHrKey INT, @nChExBedHrKey INT;
                -- Вычисление стоимости доп кровати 
                IF @nRoomId > 2 BEGIN
                    --------
                    -- Удаляем записи в которых нет доп мест
                    -------
                    IF( @nRoomId = 21 ) BEGIN
                        DELETE d FROM #tFinalTable d
                        WHERE
                            d.nId IN (
                                SELECT DISTINCT nId
                                FROM #tFinalTable
                                    LEFT JOIN tbl_costs    ON cs_svkey = 3
                                        AND CS_DATEEND > @dToday
                                        AND cs_code = nCode
                                        AND CS_SUBCODE2 = nCode2
                                                            LEFT JOIN HotelRooms   ON HR_RMKEY = nRmKey
                                        AND HR_RCKEY = nRcKey
                                        AND HR_ACKEY IN (
                                            SELECT AC_KEY FROM Accmdmentype WHERE AC_KEY = HR_ACKEY AND AC_CODE LIKE 'Ch%'
                                        )
                                WHERE
                                    HR_KEY IS NULL
                            );
                    END
            
                    -- ex BEd
                    SET @nAccmdId = 2 ;
            
                    DECLARE cHotelsMainPrices CURSOR LOCAL STATIC FORWARD_ONLY FOR
                    SELECT 
                        nId, nPrice, nTotalDays, nCode, nCode1, nCode2, nPrKey, nPkKey, nRmKey, nRcKey, nAcKey,
                        (SELECT TOP 1 HR_KEY FROM hotelrooms WHERE HR_ACKEY = @nAccmdId AND HR_RMKEY=nRmKey AND HR_RCKEY = nRcKey ) as nAdultExBedHrKey
                    FROM #tFinalTable
                    WHERE
                        nId BETWEEN @nOffset AND @nOffset + @nLimit;
            
                    OPEN cHotelsMainPrices;
                    SET @nDuration = @nDuration + 1;
                    FETCH NEXT FROM cHotelsMainPrices INTO @nId, @nPrice, @nTotalDays, @nCode, @nCode1, @nCode2, @nPrKey, @nPkKey, @nRmKey, @nRcKey, @nAcKey,@nAdultExBedHrKey
                    WHILE @@fetch_status = 0
                    BEGIN
                            -- Если 2 взр с ребенком берем ребенка с наибольшим диапозоном возраста 
                            IF( @nRoomId = 21 ) BEGIN
                                SET @nCode1 = (
                                    SELECT TOP 1 HR_KEY
                                    FROM Accmdmentype
                                         INNER JOIN HotelRooms   ON HR_ACKEY = AC_KEY
                                             AND HR_RMKEY = @nRmKey
                                             AND HR_RCKEY = @nRcKey
                                         INNER JOIN tbl_costs    ON cs_svkey = 3
                                             AND CS_DATEEND  > @dToday
                                             AND CS_CODE     = @nCode
                                             AND CS_SUBCODE1 = HR_KEY
                                             AND CS_SUBCODE2 = @nCode2
                                             AND CS_PRKEY    = @nPrKey
                                    WHERE
                                         AC_CODE LIKE 'Ch%'
                                    ORDER BY ISNULL(ac_ageto, 0) - ISNULL(AC_AGEFROM, 0) DESC
                                )
                            END
                            ELSE
                                SET @nCode1 = @nAdultExBedHrKey;
            
                            EXEC dbo.wrapper_GetServiceCost
                                @svKey = 3, @code = @nCode, @code1 = @nCode1, @code2 = @nCode2, @prKey = @nPrKey, @packetKey = @nPkKey,
                                @date = @dBeginDate, @days = @nDuration, @resRate = @sToCurrencyCode, @men = 1, @discountPercent = 0,
                                @sellDate = @dToday, @netto = null, @brutto = @nExBedPriceBrutto output, @discount = null;
            
                            UPDATE #tFinalTable SET
                                nPrice = nPrice + ISNULL(@nExBedPriceBrutto, nPrice/@nMainPax),
                                nExtraBedCode1 = @nCode1,
                                nExtraBedPrice = @nExBedPriceBrutto
                            WHERE
                                nId = @nId
                            FETCH NEXT FROM cHotelsMainPrices INTO @nId, @nPrice, @nTotalDays, @nCode, @nCode1, @nCode2, @nPrKey, @nPkKey, @nRmKey, @nRcKey, @nAcKey,@nAdultExBedHrKey
                        END
                    CLOSE cHotelsMainPrices;
                    DEALLOCATE cHotelsMainPrices;
                END
            
                SELECT *
                FROM #tFinalTable
                WHERE
                    nId BETWEEN @nOffset AND @nOffset + @nLimit;

                DROP TABLE #tCosts;
                DROP TABLE #tFinalTable;
            
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
        return 1;
        DB::statement("DROP PROCEDURE SBSN.getHotelsCost");
    }
}
