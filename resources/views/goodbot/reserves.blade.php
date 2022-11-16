@extends('layouts.app')

@section('content')
<script>const whTooltips = {colorLinks: true, iconizeLinks: true, renameLinks: true};</script>
<script src="https://wow.zamimg.com/widgets/power.js"></script>
<header class="special container">
    <h2 class="center">Raid Reserves</h2>
    <span class="icon solid fa-clipboard-check"></span>
</header>
<section class="wrapper style2 container special-alt">
    <div class="row">
        <table id="raidinfo">
            <tr>
                <td style="width: 25%">Raid Type:</td>
                <td>{{ ucfirst($raid->raid) }}</td>
            </tr>
            <tr>
                <td style="width: 25%">Raid Date:</td>
                <td>{{ date("F d, Y", strtotime($raid->date)) }} </td>
            </tr>
            @if (!empty($raid->time))
            <tr>
                <td style="width: 25%">Raid Time:</td>
                <td>{{ $raid->time }}</td>
            </tr>
            @endif
            @if ($raid->reserveLimit > 1)
            <tr>
                <td style="width: 25%">Raid Limit:</td>
                <td>{{ $raid->reserveLimit }}</td>
            </tr>
            @endif
        </table>
    </div>
</section>
<section class="wrapper style2 container special-alt">
    <div class="row">
        <table id="reserves">
            <thead>
                <tr>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 75%;">Reserve @if ($raid->reserveLimit > 1) - (Please select {{ $raid->reserveLimit }}) @endif</th>
                    <th>
                </tr>
            </thead>
        <tbody>
        @foreach ($signups AS $signup) 
            @if ($signup->signup == 'yes')
                <tr signup="{{ $signup->id }}">
                    <td>{{ $signup->player }}</td>
                    <td>
                        @if ($signup->reserve)
                            <a href="https://wotlk.wowhead.com/item/{{ $signup->reserve->item->itemID }}" id="reserve-link-{{ $signup->id }}">{{ $signup->reserve->item->name }}</a>
                        @else
                            <a id="reserve-link-{{ $signup->id }}">none</a>
                        @endif
                        <select class="reserve-select" id="reserve-select-{{ $signup->id }}" onchange="saveReserve(this.value, {{ $signup->id }});">
                            <option value="0">None</option>
                            @foreach ($items AS $item)
                                <option value="{{ $item->id }}"
                                    @if ($signup->reserve && $signup->reserve->item->id == $item->id)
                                        selected
                                    @endif
                                >{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <a onclick="showSelect({{ $signup->id }});">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function(){
        $('#reserves').tablesorter({
            sortList: [[1,0], [0,0]]
        }); 
    });
    function saveReserve(itemID, signupID) {
        window.location = '/reserve/' + signupID + '/' + itemID;
    }
    function showSelect(signupID) {
        var row = $('[signup=' + signupID + ']');
        var icon = row.find('i');
        if (icon.hasClass('fa-pencil')) {
            row.find('i').removeClass('fa-pencil').addClass('fa-ban');
            $('#reserve-select-' + signupID).show();
            $('#reserve-link-' + signupID).hide();
        } else {
            row.find('i').removeClass('fa-ban').addClass('fa-pencil');
            $('#reserve-select-' + signupID).hide();
            $('#reserve-link-' + signupID).show();
        }
    }
</script>
@endsection