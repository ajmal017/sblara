<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SharePercetageCseController extends Controller
{
    public function index()
    {
    	 	// 18 director
    	 	// 21 foreign
    	 	// 19 govt
    	 	// 20 institute
    	 	// 22 public
    	 	$fundamentals = \App\Fundamental::whereIn("meta_id", [18, 21, 19, 20, 22])->where('is_latest', 1)->orderBy('meta_date', 'desc')->get();
    	 	$date = \Carbon\Carbon::parse(\DB::select("select max(created_at) as `date` from cse_share_percentage")[0]->date)->format('Y-m-d');

    	 	$sql = "select * from cse_share_percentage left join instruments on instruments.id = instrument_id where created_at like '$date%' and sponsor is not NULL";
    	 	$cse = collect(\DB::select($sql))->keyBy('instrument_id');
    	 	$data = [];
    	 	foreach ($fundamentals as $fundamental) {
    	 		$data[$fundamental->instrument_id][$fundamental->meta_id] = $fundamental->meta_value;
    	 		$data[$fundamental->instrument_id]['meta_date'] = $fundamental->meta_date;
    	 	}
    	 

        $instruments =\App\Instrument::orderBy('instrument_code', 'asc')->whereNotIn('sector_list_id', [5, 23, 22])->where('active', '=', '1')->get();
            
        foreach ($instruments as $key => $value) {
            if(!isset($data[$value->id])){
                $data[$value->id][18] = "";
                $data[$value->id][19] = "";
                $data[$value->id][20] = "";
                $data[$value->id][21] = "";
                $data[$value->id][22] = "";
                $data[$value->id]['meta_date'] = "";

            }
        }
            $fundamentals = $data;
        // dd($instruments);
    	return view('share-percentage-cse')->with(compact(['instruments', 'cse', 'fundamentals']));
    }

    public function scrape()
    {
    	$d = date('Y-m-d');
    	$ids = collect(\DB::select('select instrument_id from cse_share_percentage where created_at like "'.$d.'%"'))->pluck('instrument_id');

    	$instruments =\App\Instrument::whereNotIn('id', $ids)->orderBy('instrument_code', 'asc')->whereNotIn('sector_list_id', [5, 23, 22])->where('active', '=', '1')->take(5)->get();
        // dd($instruments);
    	$rows = [];
    	foreach ($instruments as  $instrument) {
            // dump($instrument->instrument_code);
                          $arrContextOptions=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );  

			    	$page = file_get_contents("https://www.cse.com.bd/company/companydetails/".$instrument->instrument_code,  false, stream_context_create($arrContextOptions));
			    	$dom = new \DOMDocument();
			    	@$dom->loadHTML($page);
			    	$xpath = new \DOMXpath($dom);

			    	$data = $xpath->query('//*[@id="wrapper"]/div[1]/div/div[1]/div[2]/div[2]/div/div/div/div/div/div/div/div/div/table[3]/tbody/tr[3]/td/table/tbody/tr/td[1]/table');



                    foreach ($data->item(0)->getElementsByTagName('tr') as $key => $value) {
                      
                        if($key == 1){
                              $row = [];
                           foreach( $value->getElementsByTagName('td') as $k => $v){
                                    // dump($k);
                                    // dump($v->childNodes->item(0)->data);

                                if($k == 0){
                                    @$row['sponsor'] = $v->childNodes->item(0)->data;
                                }else if($k == 1){
                                    @$row['government'] = $v->childNodes->item(0)->data;
                                }else if($k == 2){
                                    @$row['institute'] = $v->childNodes->item(0)->data;
                                }else if($k == 3){
                                    @$row['foreign'] = $v->childNodes->item(0)->data;
                                }else if($k == 4){
                                    @$row['public'] = $v->childNodes->item(0)->data;
                                }                                
                           }

                        }else if ($key == 3){
                               foreach( $value->getElementsByTagName('td') as $k => $v){
                                       if($k == 1){
                                          $date = $v->nodeValue;
                                       }
                               }                            
                        }
                    }

                        $date = substr($date, 2);

			    	try { 
                      $date = \Carbon\Carbon::parse(trim(str_replace(",", "", $date)))->format('Y-m-d');
                      
                    } catch (\Exception $e) {
                        $date = str_replace("As on", "", $date);
                        try {
                         $date = \Carbon\Carbon::parse("last day of ".$date)->format('Y-m-d');
                        } catch (\Exception $e) {
                            dump('dsf');
                            dd($date);
			    		$date = null;
                        }
			    	}
                
			    	$row['meta_date'] = $date;
			    	$row['instrument_id'] = $instrument->id;
			    	$row['created_at'] = \Carbon\Carbon::now();

			    	$rows[] = $row; 
			    	// /html/body/div/div/div[5]/div/div[2]/table/tbody/tr[3]/td/table/tbody/tr[3]/td/table/tbody/tr[2]/td/table/tbody/tr[4]/td/table/tbody/tr/td[1]/table
    	}
    	\DB::table('cse_share_percentage')->insert($rows);
    	if(count($instruments) < 1){
    		$html = "All Done!";
    	}else{
    		$html = "Please wait! Don't close the browser. <script> location.reload(); </script>";
    	}
    	return  $html;
    }

    public function update(Request $request)
    {

    	$metas = $request->except(['_token', 'instrument_id', 'meta_date']);
        // dd($request->all());
    	$instrument_id = $request->instrument_id;
    	$meta_date = $request->meta_date;
    	foreach ($metas as $meta_id => $meta_value) {
    	$row = null;
    		$row = \App\Fundamental::where('instrument_id', $instrument_id)->where('meta_id', $meta_id)->where('meta_date', $meta_date)->first();
    		if($row == null){
    			\App\Fundamental::where('instrument_id', $instrument_id)->where('meta_id', $meta_id)->where('is_latest', 1)->update(['is_latest'=> 0]);
    			$row = new \App\Fundamental();
    			$row->is_latest = 1;
    			$row->meta_id = $meta_id;
    			$row->instrument_id = $instrument_id;
    			// dump('insert');
    		}
    		$row->meta_value = $meta_value;
    		$row->meta_date = $meta_date;
    		// dd($row);
    		$row->save();
    		// dd($row->id);

    	}
    	return redirect()->back()->with(['success' => 'Data succesfully updated']);
    }
}
