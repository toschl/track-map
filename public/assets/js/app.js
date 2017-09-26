var types = {'All': 0};
var years = {'All': 0};

function drawMap(data) {

    var bounds = new google.maps.LatLngBounds();
    var mapProp= {
        mapTypeId: google.maps.MapTypeId.TERRAIN,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

    for(var i in data) {
        if (data[i].summary_polyline) {
            var decodedLatLngs = google.maps.geometry.encoding.decodePath(data[i].summary_polyline);
            setStatus(i+1 + '/' + data.length);
            var polyline = new google.maps.Polyline({
                path: decodedLatLngs,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
                map: map
            });
            if (decodedLatLngs.length > 1) {
                bounds.extend(decodedLatLngs[0]);
                bounds.extend(decodedLatLngs.pop());
            }
            writeTracklist(data[i]);
            incrementType(data[i].type);
            incrementYear(data[i].start_date);
        }
    }

    map.fitBounds(bounds);
    setStatus('');
}

function setStatus(message) {
    $('#trackload').html(message);
}

function incrementType(type) {
    if (types[type]) {
        types[type]++;
        $('#typelist_' + type).html(type + ' (' + types[type] + ')');
    }
    else {
        types[type] = 1;
        $('#typelist').append('<li id="typelist_' + type + '">' + type + ' (' + types[type] + ')</li>');
    }

    types['All']++;
    $('#typelist_All').html('All (' + types['All'] + ')');
}

function incrementYear(start_date) {
    var year = moment(start_date).format('YYYY');
    if (years[year]) {
        years[year]++;
        $('#yearlist_' + year).html(year + ' (' + years[year] + ')');
    }
    else {
        years[year] = 1;
        $('#yearlist').append('<li id="yearlist_' + year + '">' + year + ' (' + years[year] + ')</li>');
    }

    years['All']++;
    $('#yearlist_All').html('All (' + years['All'] + ')');
}

function writeTracklist(track) {
    var formattedDate = moment(track.start_date);
    $('#tracktable > tbody:last-child').append('<tr>' +
        '<td>' + formattedDate.format('DD.MM.YY') + '</td>' +
        '<td>' + track.name + '</td>' +
        '<td>' + Math.round(track.distance/100)/10 + 'km</td>' +
        '</tr>');
}

function myMap() {
    setStatus('loading');
    $.ajax({
        type: "GET",
        url: '/data',
        dataType: 'json',
        success: function(data){
            drawMap(data);
            writeTracklist(data);
        }
    });
}