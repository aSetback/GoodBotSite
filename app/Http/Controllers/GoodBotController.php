<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Raid;
use App\RaidReserve;
use App\ReserveItem;
use App\RaidHash;
use App\Signup;
use App\Item;

class GoodBotController extends Controller
{

    public function OAuth() {
        return redirect()->route('character.servers');
    }

    public function index($raidName)
    {

        $hash = RaidHash::where('hash', $raidName)->first();
        if (!$hash) {
            abort(404);
        }
        $raids = Raid::where('memberID', $hash->memberID)
            ->where('guildID', $hash->guildID)
            ->where('date', '>', date('Y-m-d, h:i:s'))
            ->where('date', '<', date('Y-m-d, h:i:s', strtotime("+3 Months")))
            ->get();
        return view('goodbot.index')
            ->with('raidName', $raidName)
            ->with('raids', $raids);
    }

    public function signups($id)
    {
        $raid = Raid::findOrFail($id);
        $hash = RaidHash::where('memberID', $raid->memberID)
            ->where('guildID', $raid->guildID)
            ->first();
        if (!$hash) {
            abort(404);
        }
        $signups = Signup::where('raidID', $id)->get();
        return view('goodbot.signup')
            ->with('hash', $hash)
            ->with('raid', $raid)
            ->with('signups', $signups);
    }

    public function reserves($id)
    {
        $raid = Raid::with(['signups', 'signups.reserve', 'signups.reserve.item'])->where('id', $id)->first();
        $signupList = [];
        foreach ($raid->signups AS $signup) {
            $signupList[] = $signup;
        }
        usort($signupList, function($a, $b) { 
            $aItem = $a->reserve ? $a->reserve->item->name : '-';
            $bItem = $b->reserve ? $b->reserve->item->name : '-';
            return $bItem <=> $aItem;
         });
        $raidParts = explode('+', $raid->raid);
        $items = [];
        foreach ($raidParts AS $raidPart) {
            $raidItems = ReserveItem::where('raid', $raidPart)->orderBy('name')->get();
            foreach ($raidItems AS $raidItem) {
                $items[$raidItem->itemID] = $raidItem;
            }
        }

        return view('goodbot.reserves')
        ->with('raid', $raid)
        ->with('items', $items)
        ->with('signups', $signupList);
    }

    public function reserve($signupID, $itemID) {
        $signup = Signup::findOrFail($signupID);
        RaidReserve::updateOrCreate(
            ['signupID' => $signupID, 'raidID' => $signup->raidID],
            ['reserveItemID' => $itemID]
        );
        return back();
    }
}