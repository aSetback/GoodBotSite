<?php

use Illuminate\Http\Request;
use App\Raid;
use App\Signup;
use App\Character;
use App\Http\Resources\Signup AS SignupResource;
use App\Http\Resources\Raid AS RaidResource;
use App\Http\Resources\RaidFull AS RaidFullResource;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api'])->group(function() {
    Route::get('/reserve', 'APIController@reserve')->name('api.reserve');
    Route::get('/reserve/items', 'APIController@reserveItems')->name('api.reserve.items');
    Route::get('/nick', 'APIController@nick')->name('api.nick');
    Route::get('/gear/{player}/{server}/{region}', 'APIController@gear')->name('api.gear');
    Route::get('/splits/{code}', 'APIController@splits')->name('api.splits');

    Route::get('/account/{user}/{pass}', 'APIController@account')->name('api.account');
    Route::get('/gearCopy/{old}/{new}', 'APIController@gearCopy')->name('api.gearCopy');

    Route::get('/info/{character}', function(Request $request, $character) {
        $guildID = $request->get('guildID');
        if (!$guildID) {
            return ['error' => 'guildID is a required parameter.'];
        }
        
        // Attempt to find original character
        $main = Character::where(['name' => $character, 'guildID' => $guildID])->first();
        if (!$main) {
            return ['error' => 'Character does not exist.'];
        }
        
        // if MainID is set, this is not the main.
        if ($main->mainID) {
            // Retrieve the main
            $main = Character::where(['id' => $main->id, 'guildID' => $guildID])->first();
        }
        $main->isMain = true;
        $main->getSignups();

        // Retrieve all alts
        $alts = Character::where('mainID', $main->id)->get();

        // Move all characters to a single array
        $returnArray = [$main];
        foreach ($alts AS $alt) {
            $alt->getSignups();
            $returnArray[] = $alt;
        }

        // Return our array
        return $returnArray;
    });

    Route::get('/raids', function() {
        $guildID = request()->get('guildID');
        $raids = Raid::where('guildID', $guildID)
        ->where('date', '>', date('Y-m-d') . ' 00:00:00')
        ->where('date', '<', date('Y-m-d', strtotime('+3 months')))
        ->get();
        
        return RaidResource::collection($raids);
    });
    
    Route::get('/raid/{raidID}', function($raidID) {
        $guildID = request()->get('guildID');
        $raid = Raid::with(['signups', 'signups.reserve.item'])
            ->where('guildID', $guildID)
            ->find($raidID);
        
        return new RaidFullResource($raid);
    });
    
    Route::get('/signup', function(Request $request) {
        $parameters = [
            'raidID'        => $request->get('raidID'),
            'characterID'   => $request->get('characterID'),
            'signup'        => $request->get('signup')
        ];
        foreach ($parameters AS $key => $value) {
            if (empty($value)) {
                return ['error' => $key . ' is a required parameter.'];
            }
        }
        $raid       = Raid::findOrFail($parameters['raidID']);
        $character  = Character::findOrFail($parameters['characterID']);
        $signup     = Signup::updateOrCreate(
            [
                'raidID' => $parameters['raidID'], 
                'player' => $character->name,
            ],
            [
                'signup' => $parameters['signup'],
                'channelID' => $raid->channelID,
                'guildID' => $raid->guildID,
                'memberID' => $request->id
            ]);
        
            // Create the embed
        $signup->sendMessage(0, $raid->channelID, '+embed refresh');

        return ['success' => $signup];

    });
  
});