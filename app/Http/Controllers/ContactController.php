<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public $pageCount = 5;

    public function show ()
    {
        $contacts = Contact::where("user_id",Auth::user()->id)->orderBy("id","ASC")->paginate($this->pageCount);

        return $contacts;
    }

    public function create (Request $request)
    {
        if ($request->userid !== null && $request->name !== null && $request->phone !== null && strlen($request->phone) == 10){

            $phoneIsset = Contact::where("phone",$request->phone)->get()->count();

            if ($phoneIsset){
                return "isset";
            }
            elseif(!preg_match('/\d{10}/',$request->phone)){
                return "empty";
            }
            else{
                $newContact = Contact::create([
                    "user_id"       => $request->userid,
                    "name"          => $request->name,
                    "second_name"   => $request->second_name,
                    "phone"         => intval($request->phone),
                    "comment"       => $request->comment,
                ]);

                Log::create([
                    "user_id"   =>  $request->userid,
                    "event"     =>  "Append contact",
                    "result"    =>  "ok"
                ]);

                return "ok";
            }

        }
        else{
            return "empty";
        }
    }

    public function getupdate ($id)
    {
        $contact = Contact::where("user_id",Auth::user()->id)->where("id",$id)->get();

        return $contact;

    }

    public function setupdate (Request $request,$id)
    {
        if ($request->userid !== null && $request->name !== null && $request->phone !== null && strlen($request->phone) == 10){

            $updateContact = Contact::find($id);

            if(!preg_match('/\d{10}/',$request->phone)){
                return "empty";
            }
            else{
                $updateContact->name            = $request->name;
                $updateContact->second_name     = $request->second_name;
                $updateContact->phone           = $request->phone;
                $updateContact->comment         = $request->comment;
                $updateContact->save();

                Log::create([
                    "user_id"   =>  $request->userid,
                    "event"     =>  "Update contact",
                    "result"    =>  "ok"
                ]);

                return "ok";
            }
        }
        else{
            return "empty";
        }

    }


    public function remove (Request $request,$id)
    {
        // Метод удаления конитакта

        Contact::find($id)->delete();

        Log::create([
            "user_id"   =>  Auth::user()->id,
            "event"     =>  "Remove contact",
            "result"    =>  "ok"
        ]);

        $currentPage = $request->current_page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $contacts = Contact::where("user_id",Auth::user()->id)->orderBy("id","ASC")->paginate($this->pageCount);

        if ($contacts->count() > 0){
            return $contacts;
        }
        else{
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage - 1;
            });
            $contacts = Contact::where("user_id",Auth::user()->id)->orderBy("id","ASC")->paginate($this->pageCount);

            return $contacts;
        }

    }

    public function search (Request $request)
    {
        // Метод поиска по всем полям таблицы контактов

        $resultQuery = Contact::where("user_id",Auth::user()->id)->where(function ($query) use ($request){
            $query->where("name","LIKE","%".$request->search."%")
                ->orWhere("second_name","LIKE","%".$request->search."%")
                ->orWhere("phone","LIKE","%".$request->search."%")
                ->orWhere("comment","LIKE","%".$request->search."%");
        })->get();

        return $resultQuery;

    }

}
