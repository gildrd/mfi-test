$(document).ready(function () {
    $(".display-peak").click(function () {
        let id = $(this).attr("id");
        displayOnMap(id);
    });

    let myModal = document.getElementById("myModal");
    myModal.addEventListener('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('id') // Extract info from data-* attributes
        var name = button.data('name') // Extract info from data-* attributes
        var lat = button.data('lat') // Extract info from data-* attributes
        var lon = button.data('lon') // Extract info from data-* attributes
        var alt = button.data('alt') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-body #modal-peak-name').val(name)
        modal.find('.modal-body #modal-peak-lat').val(lat)
        modal.find('.modal-body #modal-peak-lon').val(lon)
        modal.find('.modal-body #modal-peak-alt').val(alt)
        modal.find('.modal-body #modal-peak-id').val(id)

        $("#modal-save").click(function () {
            $.ajax({
                url: '/api/peak/update/'+id,
                method: "PUT",
                data: JSON.stringify({
                    name: $("#modal-peak-name").val(),
                    lat: $("#modal-peak-lat").val(),
                    lon: $("#modal-peak-lon").val(),
                    altitude: $("#modal-peak-alt").val()
                }),
                contentType: "application/json"
            }).done(function() {
                document.location.href="/";
            });
        })
    })

});



function displayOnMap(id) {
    let parts = id.split("-");
    id = parts[2]
    $.get('/api/peak/'+id, function(data) {console.log(data);
        var container = L.DomUtil.get('map');
        if(container != null){
            container._leaflet_id = null;
        }
        var element = document.getElementById('map');

        var map = L.map(element);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Target's GPS coordinates.
        var target = L.latLng(data.lat, data.lon);

        // Set map's center to target with zoom 14.
        map.setView(target, 14);

        // Place a marker on the same location.
        L.marker(target).addTo(map);
    });

}