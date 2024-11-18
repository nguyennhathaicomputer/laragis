<?php

namespace App\Models;

use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'company', 'location', 'website', 'email', 'description', 'tags'];

    // public static function find($id){
    //     $listings = self::all();
    //     foreach($listings as $listing){
    //         if($listing['id'] ==$id){
    //             return $listing;
    //         }
    //     }
    // }


    public function scopeFilter($query , array $filters){
        if($filters['tag'] ?? false){
             $query->where('tags', 'like', '%'. request('tag') . '%');
        }


        if($filters['search'] ?? false){
            $query->where('title', 'like', '%'. request('search') . '%')
            ->orwhere('description', 'like', '%'. request('search') . '%')
            ->orwhere('tags', 'like', '%'. request('search') . '%');
       }

    }

        # relationship to user id
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


}
