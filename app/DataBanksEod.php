<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DataBanksEod extends Model
{
    protected $appends = array('date_timestamp');
    protected $dates = [
        'date',
    ];


    public function market()
    {
        return $this->belongsTo('App\Market');
    }

    public function getDateTimestampAttribute()
    {
        return $this->date->timestamp;
    }

    // $howManyDays can be integer and date
    // simple date. no carbon obj
    public static function getEodByInstrument($instrumentId=0,$howManyDays=180,$toDate=null)
    {
        //dd("$howManyDays -> $toDate");
        $now = Carbon::now();
        // Setting today as to_date
        if(is_null($toDate))
        {
            $toDate=$now->format('Y-m-d');
        }

        if(is_int($howManyDays)) {
        $d=$now->subDays($howManyDays);
        $fromDate=$d->format('Y-m-d');
        }else
        {
            $fromDate=$howManyDays;
        }

        $eodData= static::whereBetween('date', [$fromDate, $toDate])->where('instrument_id',$instrumentId)->orderBy('date', 'desc')->get();

        $dataBankallGroup = $eodData->groupBy('market_id');

        $eodData=array();
        //eliminating duplicate if exist (some duplicate data available. We have to prevent this in future)
        foreach ($dataBankallGroup as $eachTradeDate) {
            $volume=0;
            foreach($eachTradeDate as $eachData)  // to eliminate duplicate data. We will take higher volume data
            {

                if($eachData->volume>$volume)
                {
                    $data=clone $eachData;
                    $volume=$eachData->volume;
                }
            }
            $eodData[]=$data;
        }

        return collect($eodData);

    }


}
