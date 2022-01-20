function initMap() {

    const thisLatLng = {"lat": 15.5023, "lng": 73.9117};

    map = new google.maps.Map(document.getElementById("map"), {
	center: thisLatLng,
	zoom: 12,
    });

    new google.maps.Marker({
	position: thisLatLng,
	map,
	title: "Hello World!",
    });
}
