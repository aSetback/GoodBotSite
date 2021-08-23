@extends('layouts.dashboard')

@section('content')

<script>const whTooltips = {"iconizeLinks": false, "hide": { "droppedby": true, "dropchance": true}};</script>
<script src="https://wow.zamimg.com/widgets/power.js"></script>
<div id="gear-modal" class="no-modal">
    <div id="gear-div">
        <div id="gear-side">
            <a class="gear-box" slot="Head"></a>
            <a class="gear-box" slot="Neck"></a>
            <a class="gear-box" slot="Shoulders"></a>
            <a class="gear-box" slot="Cloak"></a>
            <a class="gear-box" slot="Chest"></a>
            <a class="gear-box" slot="Shift"></a>
            <a class="gear-box" slot="Tabard"></a>
            <a class="gear-box" slot="Bracers"></a>
        </div>
        <div id="gear-middle">
            <div class="gear-player">
                Player
            </div>
            <div class="gear-lastseen">
                Last Seen
            </div>
            <div class="gear-loading">
                Loading<br />
                <img src="/assets/img/loading.gif" />
                <br />
            </div>
            <div class="gear-bottom">
                <a class="gear-box" slot="Main Hand"></a>
                <a class="gear-box" slot="Off Hand"></a>
                <a class="gear-box" slot="Ranged"></a>
            </div>
        </div>
        <div id="gear-side">
            <a class="gear-box" slot="Gloves"></a>
            <a class="gear-box" slot="Belt"></a>
            <a class="gear-box" slot="Legs"></a>
            <a class="gear-box" slot="Boots"></a>
            <a class="gear-box" slot="Ring1"></a>
            <a class="gear-box" slot="Ring2"></a>
            <a class="gear-box" slot="Trinket1"></a>
            <a class="gear-box" slot="Trinket2"></a>
        </div>
    </div>
</div>
<br /><br />

@endsection

@section('scripts')
    <script>
        function gearCheck(player) {
            $('.gear-loading').show();
            $('.gear-lastseen, .gear-player').hide();
            $('.gear-box').css('background-image', '');
            $('#gear-modal').show();
            $.ajax({
                url: '/gear/' + player + '/Mankrik/US',
                success: function(data) {
                    $('.gear-loading').hide();
                    $('.gear-player').html(player).show();
                    for (slot in data.items) {
                        console.log(data.items[slot]);
                        try {
                            if (slot == "Rings") {
                                loadItem($('[slot="Ring1"]'), Object.values(data.items[slot])[0]);
                                loadItem($('[slot="Ring2"]'), Object.values(data.items[slot])[1]);
                            } else if (slot == "Trinkets") {
                                loadItem($('[slot="Trinket1"]'), Object.values(data.items[slot])[0]);
                                loadItem($('[slot="Trinket2"]'), Object.values(data.items[slot])[1]);
                            } else {
                                loadItem($('[slot="' + slot + '"]'), Object.values(data.items[slot])[0]);
                            }
                        } catch (e) {

                        }
                    }
                    $('.gear-lastseen').html('Last Seen: ' + data.date).show();
                }
            })
        }
        function loadItem(element, item) {
            var count = item.count;
            var oldCount = element.attr('count');
            console.log(item);
            if (oldCount < count) {
                return false;
            }
            item = item.item;
            if (item.id == 0) {
                element.css('background-image', '');
                element.attr('href', '');
            } else {
                if (item.permanentEnchant) {
                    element.attr('href', 'https://tbc.wowhead.com/item=' + item.id + '&ench=' + item.permanentEnchant);
                } else {
                    element.attr('href', 'https://tbc.wowhead.com/item=' + item.id);
                }
                if (item.gems) {
                    var gemList = [];
                    for (key in item.gems) {
                        gemList.push(item.gems[key].id);
                    }
                    if (item.permanentEnchant) {
                        element.attr('href', element.attr('href') + '&gems=' + gemList.join(':'));
                    } else {
                        element.attr('href', element.attr('href') + '?gems=' + gemList.join(':'));
                    }
                }
                element.css('background-image', 'url(https://wow.zamimg.com/images/wow/icons/large/' + item.icon + ')');
                element.attr('quality', item.quality);
                element.attr('count', count);
            }
        }
        gearCheck('{{ $character }}');
</script>
@endsection