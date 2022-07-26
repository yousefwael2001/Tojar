<?php
namespace App\Http\Controllers\Tojars;
use App\Http\Controllers\Controller;
use App\Http\Requests\TojarRequest;
use App\Models\tojar;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class TojarController extends Controller
{
    //view

    public  function create()
    {
        $key=['كرم ابو سالم'=>'كرم ابو سالم','بيتونيا'=>'بيتونيا','ترقوميا'=>'ترقوميا'
            ,'معبر رفح'=>'معبر رفح' ,'شعار فرايم'=>'شعار فرايم'];
        return view('tojars.TojarCreate')->with('key' ,$key);
    }

    //create

     public function store(TojarRequest $request)
    {
        //Request

        $MerchantName2=$request['MerchantName2'];
        $Date=$request['Date'];
        $crossing=$request['crossing'];
        $Merchantsphone2=$request['Merchantsphone2'];
        $IdentificationNumber2=$request['IdentificationNumber2'];
        $invoiceNumber2=$request['invoiceNumber2'];
        $Paymentstatus=$request['Paymentstatus'];
        $path = public_path('tmp/uploads');

        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        $file = $request->file('image');
        $fileName = uniqid('', true) . '_' . trim($file->getClientOriginalName());
        $file->move($path, $fileName);
        foreach($request->input('MerchantName') as $key => $value) {

            //model
            $tojar=new tojar();
            $tojar->MerchantName = $request->get('MerchantName')[$key];
            $tojar->Merchantsphone = $request->get('Merchantsphone')[$key];
            $tojar->IdentificationNumber = $request->get('IdentificationNumber')[$key];
            $tojar->invoiceNumber =$request->get('invoiceNumber')[$key];
            $tojar->DriverID =$request->get('DriverID')[$key];
            $tojar->ThedriverName =$request->get('ThedriverName')[$key];
            $tojar->carNumber =$request->get('carNumber')[$key];
            $tojar->DriverMobileNumber =$request->get('DriverMobileNumber')[$key];
            $tojar->Receiptnumber =$request->get('Receiptnumber')[$key];
           $tojar->checkboxs=implode(',',$request->checkboxs);
            $tojar->MerchantName2=$MerchantName2;
            $tojar->Date=$Date;
            $tojar->crossing=$crossing;
            $tojar->IdentificationNumber2=$IdentificationNumber2;
            $tojar->Merchantsphone2=$Merchantsphone2;
            $tojar->invoiceNumber2=$invoiceNumber2;
            $tojar->Paymentstatus=$Paymentstatus;
            $result= $tojar->image='http://localhost:81/tojar/public/tmp/uploads/'.$fileName;
            $tojar->save();
        }
        return redirect('tojar/create')->with('massages_session',$result);

    }

    //select

    #[NoReturn] public function index (Request $request)
    {

        //model

        define('pagination',3);
        $tojar=tojar::select('*')
            ->paginate(pagination);
        foreach($tojar as $tojars) {
            $im =url($tojars->image);
            $tojars->image=$im;

            }
       return view('tojars.TojarHome')->with('tojar',$tojar);
    }

    //edit

    public function  edit ($id)
    {
            //model

        $tojar=tojar::where('id',$id)
            ->first();
        $im =url($tojar->image);
        $tojar->image=$im;
        $key=['كرم ابو سالم'=>'كرم ابو سالم','بيتونيا'=>'بيتونيا','ترقوميا'=>'ترقوميا'
            ,'معبر رفح'=>'معبر رفح' ,'شعار فرايم'=>'شعار فرايم'];

        return view('tojars.edit')->with('tojar',$tojar)->with('key' ,$key);


    }


    public function  update (Request $request,$id){
        //Request

        $name=$request['name'];
        $Brith_Date=$request['Brith_Date'];
        $Nationality=$request['Nationality'];
        $price=$request['price'];
        $discount=$request['discount'];
        $tax=$request['tax'];
        $path = $request->file('image')->store('public/images');


          //model
        $tojar=tojar::where('id',$id)->first();
        $tojar->name=$name;
        $tojar->Brith_Date=$Brith_Date;
        $tojar->Nationality=$Nationality;
        $tojar->price=$price;
        $tojar->discount=$discount;
        $tojar->tax=$tax;
        $tojar->image=$path;
        $tojar->save();
        return redirect('tojar');
    }

    public  function  destroy ($id)
    {

        //row Builder

//        $query=" DELETE FROM tojar WHERE id =$id";
//        DB::delete($query);
      //  dd($reselt.'yes is  delete');



      //  Query Builder
//        DB::table('tojar')
//            ->where('id',$id)
//            ->delete();



        //model

        tojar::where('id',$id)
            ->delete();
        return redirect('tojar');




    }



}
