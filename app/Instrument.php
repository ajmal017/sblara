<?php

namespace App;
use App\Repositories\DataBankEodRepository;
use App\Repositories\DataBanksIntradayRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use DB;

class Instrument extends Model
{
    /**
     * Get the latest data bank intraday associated with the instrument.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */


   /* // Category model
    public function latestEod()
    {
        return $this->hasOne('App\DataBanksEod')->latest('date')->take(1);
        //return $this->hasOne(Measure::class)->latest()->first();
    }


    public function data_banks_eods()
    {
        return $this->hasMany('App\DataBanksEod', 'instrument_id');
    }*/


    public function data_banks_eods()
    {
        return $this->hasMany(DataBanksEod::class);
    }


    public function dataBanksEods()
    {
        return $this->hasMany(DataBanksEod::class);
    }

    public function latestDataBanksEod()
    {
        return $this->hasOne(DataBanksEod::class)->latest('date');
    }

    public function data_banks_intraday()
    {
        return $this->hasOne(DataBanksIntraday::class)->where('batch', $this->batch_id);
    }
    public function data_banks_intradays()
    {
        return $this->hasOne(DataBanksIntraday::class)->take(1)->orderBy('id', 'desc');
    }

 


   /* public function data_banks_eod()
    {
        return $this->hasMany(DataBanksEod::class)
                    ->latest('date');
    }*/

    public function sector_list()
    {
        return $this->belongsTo('App\SectorList','sector_list_id');
    }

    public static function getInstrumentsBySectorName($sectorName='Bank',$exchangeId=0)
    {
        /*We will use session value of active_exchange_id as default if exist*/
        if(!$exchangeId) {
            $exchangeId = session('active_exchange_id', 1);
        }

        $returnData = static::whereHas('sector_list', function($q) use($sectorName,$exchangeId) {
            $q->where('name', 'like', "$sectorName");
            $q->where('exchange_id', $exchangeId);
        })->where('active','1')->orderBy('instrument_code', 'asc')->get();


        return $returnData;

    }


    public static function getInstrumentsAll($exchangeId=0)
    {

        /*We will use session value of active_exchange_id as default if exist*/
        if(!$exchangeId) {
            $exchangeId = session('active_exchange_id', 1);
        }


        $cacheVar="InstrumentList$exchangeId";
        $returnData = Cache::remember("$cacheVar", 1, function ()  use ($exchangeId)  {
            $returnData=static::where('exchange_id',$exchangeId)->where('active',"1")->orderBy('instrument_code', 'asc')->get();
            return $returnData;

        });

        return $returnData;
    }



    /*
     * It will avoid index.
     * */
    public static function getInstrumentsScripOnly($exchangeId=0)
    {

        /*We will use session value of active_exchange_id as default if exist*/
        if(!$exchangeId) {
            $exchangeId = session('active_exchange_id', 1);
        }

        $cacheVar="InstrumentsScripOnly$exchangeId";

        $returnData = Cache::remember("$cacheVar", 1, function ()  use ($exchangeId)  {

            $returnData = static::whereHas('sector_list', function($q) use($exchangeId) {
                $q->where('exchange_id', $exchangeId);
                $q->where('name', 'not like', "Index");
                $q->where('name', 'not like', "custom_index");
                $q->where('name', 'not like', "Debenture");
                $q->where('name', 'not like', "Treasury Bond");
            })->where('active','1')->orderBy('instrument_code', 'asc')->get();

            return $returnData;
        });

        return $returnData;


    }

    public static function getInstrumentsScripOnlyByDB($exchangeId=0){
      if(!$exchangeId) {
          $exchangeId = session('active_exchange_id', 1);
      }

      $sql = "select `id` ,`instrument_code` from `instruments` where exists (select * from `sector_lists` where `instruments`.`sector_list_id` = `sector_lists`.`id` and `exchange_id` = '".$exchangeId."' and `name` not like 'Index' and `name` not like 'Debenture' and `name` not like 'Treasury Bond') and `active` = '1' order by `id` asc";
      $instruments = DB::Select($sql);

      return $instruments;
    }

    public static function getInstrumentsScripWithIndex($exchangeId=0)
    {

        /*We will use session value of active_exchange_id as default if exist*/
        if(!$exchangeId) {
            $exchangeId = session('active_exchange_id', 1);
        }

        $cacheVar="InstrumentsScripWithIndex$exchangeId";

        $returnData = Cache::remember("$cacheVar", 1, function ()  use ($exchangeId)  {

            $returnData = static::whereHas('sector_list', function($q) use($exchangeId) {
                $q->where('exchange_id', $exchangeId);
                $q->where('name', 'not like', "Debenture");
                $q->where('name', 'not like', "Treasury Bond");
            })->where('active','1')->orderBy('instrument_code', 'asc')->get();

            return $returnData;
        });

        return $returnData;


    }

    public static function queryInstruments($query,$exchangeId=0)
    {
        $instrumentList=self::getInstrumentsScripWithIndex($exchangeId);
        $result = $instrumentList->filter(function ($value, $key) use ($query) {
            // select this row if strstr is true
            return strstr($value->instrument_code,$query);
        });

        return $result;

    }

    /*
    * This will return last traded data for all shares of $instrumentIDs regardless date.
    * Some share may not be traded for last 2/3 days. DataBanksIntradayRepository::getLatestTradeDataAll() will return only last day data without those instruments
    * So for this reason we are writing this method
    *
    * $instrumentIDs= array of instruments id
    * $tradeDate =  If set/not null, it will count data before that day
    *
    * We dont need exchange_id here as instruments_id are coming from desired exchange
    *
    * */


    public static function getDateLessTradeData($instrumentIDs = array())
    {
        /*We will use session value of active_trade_date as default if exist*/
        $tradeDate = session('active_trade_date', null);

        if (is_null($tradeDate)) {

        /*    $lastTradedDataAllInstruments = Instrument::whereIn('id', $instrumentIDs)
                ->with(['data_banks_eod' => function ($q) {
                    $q->latest('date')->take(1);
                }])
                ->get();*/

            $lastTradedDataAllInstruments = Instrument::with(['data_banks_eod' => function ($q) {
                    $q->latest('date')->take(1);
                }])
                ->get();


        } else {
            $lastTradedDataAllInstruments = Instrument::whereIn('id', $instrumentIDs)
                ->with(['data_banks_eod' => function ($q, $tradeDate) {
                    $q->whereDate('date', '<=', $tradeDate)->latest('date')->take(1);
                }])
                ->get();
        }
        //  dump($instrumentIDs);
        //   dd($lastTradedDataAllInstruments->toArray());

        return $lastTradedDataAllInstruments;


    }

    public function corporateActionsChartData()
    {
  
        $data = $this->dataBanksEods()->select('date', 'close')->latest('date')->take(800)->get()->toArray();
       $data = array_map(function ($value)
        {
            $value = [ Carbon::parse($value['date']), $value['close']];

            return $value;
        }, $data);
       // dd($data);
       return json_encode($data);
    }

    public function getYearEndAttribute()
    {
        return (\App\Repositories\FundamentalRepository::getFundamentalData(['year_end', "q1_eps_cont_op","half_year_eps_cont_op","q3_nine_months_eps","earning_per_share"], [$this->id]));
        // return $this
    }

    public function epsHistory()
    {
        $yearEnd = $this->yearEnd;
        $data = [];
        $data['category'] = [];
        $data['q1_eps_cont_op'] = [];
        $data['earning_per_share'] = [];
        $data['q3_nine_months_eps'] = [];
        $data['half_year_eps_cont_op'] = [];


        return $data;
    }

    public function dseSharePercentage()
    {
        return $this->hasOne(DseSharePercentage::class)        ;
    }

    public function getLtpAttribute()
    {
        return  @\App\Repositories\DataBanksIntradayRepository::getAvailableLTP([$this->id])[0]->close_price;
    }

    public function getLastintradayAttribute()
    {
        if(!isset(\App\Repositories\DataBanksIntradayRepository::getAvailableLTP([$this->id])[0]))
            {
                return null;
            }
        return \App\Repositories\DataBanksIntradayRepository::getAvailableLTP([$this->id])[0];
    }

    public function metaValuesByKey($keys = [])
    {
         return  \App\Repositories\FundamentalRepository::getFundamentalData($keys, array($this->id));
  
    }

    public function eod()
    {
        return $this->hasOne(DataBanksEod::class)->latest('id');
    }

    public static function intraday()
    {
        if(request()->has('range') && request()->range != "D"){return self::intradayRange();}

        $data = static::select(\DB::raw('*, round(((close_price - yday_close_price)/close_price)*100, 2) as gain '))->whereNotIn('sector_list_id', [4, 5, 22, 23, 24 ])->where('batch_id', '!=', null)->where('data_banks_intradays.trade_date', lastTradeDate())->join('data_banks_intradays', function ($join)
        {
            $join->on('data_banks_intradays.batch', '=', 'instruments.batch_id');
            $join->on('data_banks_intradays.instrument_id', '=', 'instruments.id');
        });
        return $data;
    }
    public static function intradayRange()
    {
        $range = request()->range;
        if($range == 'W'){
            $range = 7;
        }
        if($range == 'M'){
            $range = 30;
        }
        if($range == 'Y'){
            $range = 365;
        }
        $shares = self::allshares();

        $date = Carbon::now()->subDays($range)->format('Y-m-d');
        foreach ($shares as $instrument) {
            $prev = \App\Repositories\FileDataRepository::getEodByDate($date, 'c', $instrument->instrument_id);
            $change = $instrument->close_price - $prev;
            if($change == 0 || $prev == 0){ // need to find latest prev if prev = 0           
                continue;
            }

            $gain = number_format(($change*100)/$prev, 2);
            $instrument->gain = $gain;
        }
        if(request()->panel == 'topGainer'){
            return $shares->sortByDesc('gain')->take(10);
        }else if(request()->panel == 'topLoser'){
            return $shares->sortBy('gain')->take(10);
        }
    }
    public static function topGainer()
    {
        if(request()->has('range') && request()->range != 'D'){
            return static::intraday();
        }
        $data = static::intraday()->take(10)->orderBy('gain', 'desc')->get();
        return $data;
    } 

    public static function topValue()
    {
        if(request()->has('range') && request()->range != 'D'){
            return static::intraday();
        }
        $data = static::intraday()->take(10)->orderBy('total_value', 'desc')->get();
        return $data;
    } 
    public static function topVolume()
    {
        if(request()->has('range') && request()->range != 'D'){
            return static::intraday();
        }
        $data = static::intraday()->take(10)->orderBy('total_volume', 'desc')->get();
        return $data;
    }    

    public static function topLoser()
    {
        if(request()->has('range') && request()->range != 'D'){
            return static::intraday();
        }
        $data = static::intraday()->take(10)->orderBy('gain', 'asc')->get();
        return $data;
    }    


    public static function listForTvById($ids)
    {
        $data = static::select(\DB::raw('*, round(((close_price - yday_close_price)/close_price)*100, 2) as gain '))->whereIn('instrument_id',  $ids)->where('batch_id', '!=', null)->join('data_banks_intradays', function ($join)
        {
            $join->on('data_banks_intradays.batch', '=', 'instruments.batch_id');
            $join->on('data_banks_intradays.instrument_id', '=', 'instruments.id');
        })->orderBy('instrument_code', 'asc')->get();
       
        return $data;
    }    

    public static function allshares()
    {
        $data = static::select(\DB::raw('*, round(((close_price - yday_close_price)/close_price)*100, 2) as gain, sector_lists.name as sector_name, LEFT(quote_bases , 1) as category'))->where('batch_id', '!=', null)->join('data_banks_intradays', function ($join)
        {
            $join->on('data_banks_intradays.batch', '=', 'instruments.batch_id');
            $join->on('data_banks_intradays.instrument_id', '=', 'instruments.id');
        })->orderBy('instrument_code', 'asc');
        if(request()->has('category') && request()->category != 'All'){
            $data->where('quote_bases', 'like', request()->category."%");
        }
        if(request()->has('sector') && request()->sector != 'All'){
            $data->where('sector_list_id', request()->sector);
        }
        $data->leftJoin('sector_lists', 'sector_lists.id', 'instruments.sector_list_id');
        $data->whereNotIn('sector_list_id', [22, 23, 24]);
        $data = $data->get();
        return $data;        
    }

    public static function significantTrade()
    {
        $data = \DB::select(\DB::raw("
            SELECT a.instrument_id, instruments.instrument_code, a.close_price, a.total_trades, a.close_price, round(((a.close_price - a.yday_close_price)/a.close_price)*100, 2) as gain , a.trade_date, a.trade_time, a.batch,  b.close_price, b.total_trades, b.trade_time, b.batch, (a.total_trades- b.total_trades) AS `change` FROM data_banks_intradays a 
                JOIN data_banks_intradays b ON b.batch = a.batch - 1 AND b.`instrument_id` = a. instrument_id 
                left join instruments on instruments.id = a.instrument_id
                WHERE a.batch = ".lastBatch()." 
                AND instruments.sector_list_id not in (23, 22, 24) 
                ORDER BY `change` DESC limit 10; 
            "));
        return $data;
    }   

    public static function significantValue()
    {
        $data = \DB::select(\DB::raw("
            SELECT a.instrument_id, instruments.instrument_code, a.close_price, a.total_trades, a.close_price, round(((a.close_price - a.yday_close_price)/a.close_price)*100, 2) as gain , a.trade_date, a.trade_time, a.batch,  b.close_price, b.total_trades, b.trade_time, b.batch, (a.total_value- b.total_value) AS `change` FROM data_banks_intradays a 
                JOIN data_banks_intradays b ON b.batch = a.batch - 1 AND b.`instrument_id` = a. instrument_id 
                left join instruments on instruments.id = a.instrument_id
                WHERE a.batch = ".lastBatch()." 
                AND instruments.sector_list_id not in (23, 22, 24) 
                ORDER BY `change` DESC limit 10; 
            "));
        return $data;
    }

    public static function active()
    {
        return static::whereNotIn('sector_list_id', [22, 23, 24]);
    }

}
