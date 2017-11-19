var sports = {'All': 0};
var years = {'All': 0};

function drawMap(data) {

    var bounds = new google.maps.LatLngBounds();
    var mapProp= {
        mapTypeId: google.maps.MapTypeId.TERRAIN,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    var infoWindow = new google.maps.InfoWindow();

    for (var i = 0; i < data.length; i++) {
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
            incrementSport(data[i].sport);
            incrementYear(data[i].start_date);
            contentString = '<div class="infowindow">' +
                '<h1>' +
                data[i].name +
                '</h1>' +
                '<ul>' +
                '<li>Date: ' + data[i].start_date + '</li>' +
                '<li>Type: ' + data[i].sport + '</li>' +
                '<li>Distance: ' + Math.round(data[i].distance/100)/10 + 'km</li>' +
                '</ul>' +
                '</div>';
            google.maps.event.addListener(polyline, "click", function(polyline, content) {
                return function(event) {
                    infoWindow.setContent(content);
                    infoWindow.setPosition(event.latLng);
                    infoWindow.open(map);
                };
            }(polyline,contentString));
        }
    }

    map.fitBounds(bounds);
    setStatus('');
}

function setStatus(message) {
    $('#trackload').html(message);
}

function incrementSport(sport) {
    if (sports[sport]) {
        sports[sport]++;
        $('#sportlist_' + sport).html(sport + ' (' + sports[sport] + ')');
    }
    else {
        sports[sport] = 1;
        $('#sportlist').append('<li id="sportlist_' + sport + '">' + sport + ' (' + sports[sport] + ')</li>');
    }

    sports['All']++;
    $('#sportlist_All').html('All (' + sports['All'] + ')');
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
        '<td>' + track.sport + '</td>' +
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
        }
    });
}