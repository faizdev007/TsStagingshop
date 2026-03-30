<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    //

    /**
     * Accessor:
     * Formatted date of the subscription creation date, used in the Admin
     *
     */
    public function getDisplayDateAttribute()
    {
        //$value = $this->created_at->format('d/m/Y, g:i a');
        $value = admin_date($this->created_at);
        return $value;
    }


    /**
     *  Subscriber Search
     */

     public function searchWhere($criteria=[])
     {
         $where = [];

         if(!empty($criteria['sevenDays'])){
              $date_7days_before = date('Y-m-d H:i:s',strtotime('-7 days'));
              $date_now = date('Y-m-d H:i:s');
              $where[] = ['created_at', '>=', $date_7days_before];
              $where[] = ['created_at', '<=', $date_now];
         }

         if(!empty($criteria['q'])){
             $where[] = ['email', 'LIKE', '%'.$criteria['q'].'%'];
         }

         $query = Subscriber::where($where);
         $subscribers = $query->orderby('created_at', 'DESC')->paginate(20);

         return $subscribers;
     }

}
