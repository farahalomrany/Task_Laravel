<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\OrderDetails;
use App\Category;
use App\Department;
use App\Item;
class ReplayController extends Controller
{
    //replay order by chief and distribute meals to their own department
    public function replay(Request $request)
    {
        $token_user= User::where('id','=',$request->user_id)->first();//fetch user to check authenticated
        if($token_user->token!=NULL)//user will authenticated
        {    
            $user = User::where('id','=',$request->user_id)->first();
            if($user->rol_id==1)//check chief role 1
            {
                $hotmeals = [];
                $coldmeals = [];
                $items = [];
                $i=0;
                //fetch all order details for order
                $orderitems = OrderDetails::all()->where('order_id','=',$request->order_id);
                foreach($orderitems as $oi)
                {
                    $item = Item::all()->where('id','=',$oi->item_id)->first();//fetch item by id
                    $items[$i]=$item;//all items in order
                    $i++;
                }
                $f=0;
                $o=0;
                foreach($items as $it)
                {
                    $cate = Category::where('id','=',$it->category_id)->first();//fetch item by category
                    $department = Department::where('id','=',$cate->department_id)->first();//fetch category by department id
                    if($department->title == "HotMeals")//get department name and fill the array with their items
                    {
                        $hotmeals[$f]=$it;
                        $f++;
                    }
                    if($department->title == "ColdMeals")
                    {
                        $coldmeals[$o]=$it;
                        $o++;
                    }
                }
                $response['hotmeals to hot department'] = $hotmeals;
                $response['coldmeals to cold department'] = $coldmeals;
                $response['message'] = "success";
                $response['status_code'] = 200;
                return response()->json($response,200) ;
            }
            else
            {
                $response['message'] = "you are not chief";
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
