<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Table;
use App\OrderDetails;
use App\Item;
use App\User;
class OrderController extends Controller
{
    public function store(Request $request){//store order function
        $token_user= User::where('id','=',$request->user_id)->first();//fetch user to check authenticated
        if($token_user->token!=NULL)//user will authenticated
        {
        $order = new Order;
        $order->table_id = $request->table_id;
        $table = Table::where('id','=', $order->table_id )->first();//fetch by table id
        $table->status=1;//change table status
        $table->save();

        $order->order_date = $request->order_date;
        $order->payment_state = $request->payment_state;
        $order->payment_method = $request->payment_method;
        $order->status = $request->status;
        $order->user_id = $request->user_id;
        $order->discount_amount = $request->discount_amount;
        $order->notes = $request->notes;
        $order->client_name = $request->client_name;
        
        $order->save();
        //order details store
        $j=0;
        $orderitem = [];
        $detailsid = [];
        for ($i = 0; $i < count($request->item); $i=$i+2)
            {
                $orderdetails = new OrderDetails;
                $orderdetails->order_id = $order->id;
                $orderdetails->item_id = $request->item[$i];//item id
                $orderdetails->count = $request->item[$i+1];//item count
                $orderdetails->status = 1;

                $item_price = Item::where('id','=', $orderdetails->item_id)->first();//fetch item by id
                 
                //calculate total price for item
                $orderdetails->total_price = $item_price->sell_price * $orderdetails->count;
                
                $orderdetails->save();
                $detailsid[$j]=$orderdetails->id;//store order details id for use it later to calculate price  
                $j+=1;

            }  
        ///////////// end order details store  
        $countt=0;
        foreach($detailsid as $d)
        {
            $orderdet_total_price = OrderDetails::where('id','=', $d)->first();
            $countt += $orderdet_total_price->total_price;//calculate total price for order
   
        }
          
        $order->total_price = $countt;//order total price

    	$order->consuption_taxes = $order->total_price * 0.1;
     	$order->rebuild_taxes = $order->total_price * 0.05;
    	$order->local_adminstration = $order->total_price * 0.01 ;
        $order->taxes = $order->consuption_taxes + $order->rebuild_taxes + $order->local_adminstration ;//calculate taxe
        $order->total_after_taxes = $order->total_price +  $order->taxes ;//calculate price with taxes  
       
        $order->save();
         $response['data'] = $order;
         $response['message'] = "store success";
         $response['status_code'] = 200;
         return response()->json($response,200) ;
    }
    else
    {
        $response['message'] = "user not authenticated";
    
        return response()->json($response) ;
    }
        }




    public function print_order_and_orderdetails(Request $request)
    {
        $token_user= User::where('id','=',$request->user_id)->first();//fetch user to check authenticated
        if($token_user->token!=NULL)//user will authenticated
        {
            $user = User::where('id','=',$request->user_id)->first();
            if($user->rol_id==4)//check role id for capitan 4
            {
                $order = Order::all()->where('id','=',$request->order_id)->first();//fetch order
                $orderdetails = OrderDetails::all()->where('order_id','=',$request->order_id);//fetch order details
                $response['order'] = $order;
                $response['order details'] = $orderdetails;
                return response()->json($response) ;
            }
            else
            {
                $response['message'] = "you are not capitan";
                return response()->json($response) ;

            }
        }
        else
        {
            $response['message'] = "user not authenticated";
        
            return response()->json($response) ;
        }
    }

    
}
