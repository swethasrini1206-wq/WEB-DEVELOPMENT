<?php
// index.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ChennAI — Smart Chennai Recommendations</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="assets1.css">
  <style>
    .controls-row { gap: 8px; }
    .map-wrap { border-radius: 0 0 12px 12px; overflow: hidden; }
    .tab-card { margin-bottom: 18px; }
    .tab-pane.fade .tab-pane-inner { opacity: 0; transform: translateY(8px); }
    .tab-pane.show .tab-pane-inner { opacity: 1; transform: translateY(0); }

    /* Ensure map containers have proper height */
    #map, #map-crowd, #map-food, #map-hotel {
      height: 400px;
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <h3>ChennAI — Crowd / Food / Hotel Recommendations (Chennai)</h3>

    <div class="row mb-3">
      <div class="col-md-8">
        <input id="placeInput" class="form-control" placeholder="Type a place in Chennai (e.g., Marina Beach, Mylapore, T. Nagar)" />
      </div>
      <div class="col-md-4">
        <button id="searchBtn" class="btn btn-primary w-100">Search</button>
      </div>
    </div>

    <div class="tab-card">
      <ul class="nav nav-tabs p-3" id="mainTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">Overview</button></li>
        <li class="nav-item"><button class="nav-link" id="crowd-tab" data-bs-toggle="tab" data-bs-target="#crowd" type="button">Crowd</button></li>
        <li class="nav-item"><button class="nav-link" id="food-tab" data-bs-toggle="tab" data-bs-target="#food" type="button">Food</button></li>
        <li class="nav-item"><button class="nav-link" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel" type="button">Hotel</button></li>
      </ul>

      <div class="tab-content position-relative" id="tabContents" style="min-height:420px;">
        <!-- OVERVIEW -->
        <div class="tab-pane fade show active" id="overview">
          <div class="tab-bg bg-overview"></div>
          <div class="tab-overlay"></div>
          <div class="tab-foreground tab-pane-inner p-3">
            <div id="map" class="mb-3"></div>
            <div id="overviewResults"></div>
          </div>
        </div>

        <!-- CROWD -->
        <div class="tab-pane fade" id="crowd">
          <div class="tab-bg bg-crowd"></div>
          <div class="tab-overlay"></div>
          <div class="tab-foreground tab-pane-inner p-3">
            <div class="row controls-row mb-2">
              <div class="col-md-5">
                <label class="form-label fw-bold">Preferred visiting time</label>
                <input type="time" id="visitTime" class="form-control" />
              </div>
              <div class="col-md-4">
                <label class="form-label fw-bold">Also show nearby best spots</label>
                <select id="nearbyFilter" class="form-control">
                  <option value="all">All</option>
                  <option value="restaurants">Restaurants</option>
                  <option value="hotels">Hotels</option>
                </select>
              </div>
              <div class="col-md-3 align-self-end">
                <button id="checkCrowdBtn" class="btn btn-outline-primary w-100">Check crowd & spots</button>
              </div>
            </div>
            <div id="map-crowd" class="mb-3"></div>
            <div id="crowdResult"></div>
          </div>
        </div>

        <!-- FOOD -->
        <div class="tab-pane fade" id="food">
          <div class="tab-bg bg-food"></div>
          <div class="tab-overlay"></div>
          <div class="tab-foreground tab-pane-inner p-3">
            <div class="row controls-row mb-2">
              <div class="col-md-4">
                <label class="form-label fw-bold">Budget</label>
                <select id="foodBudget" class="form-control">
                  <option value="">Any</option>
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-bold">Cuisine</label>
                <input id="cuisine" class="form-control" placeholder="Indian, Chinese, Cafe..." />
              </div>
              <div class="col-md-3 align-self-end">
                <button id="searchFoodBtn" class="btn btn-outline-success w-100">Find Food</button>
              </div>
            </div>
            <div id="map-food" class="mb-3"></div>
            <div id="foodResults"></div>
          </div>
        </div>

        <!-- HOTEL -->
        <div class="tab-pane fade" id="hotel">
          <div class="tab-bg bg-hotel"></div>
          <div class="tab-overlay"></div>
          <div class="tab-foreground tab-pane-inner p-3">
            <div class="row controls-row mb-2">
              <div class="col-md-4">
                <label class="form-label fw-bold">Budget</label>
                <select id="hotelBudget" class="form-control">
                  <option value="">Any</option>
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-bold">Stay Type</label>
                <input id="stayType" class="form-control" placeholder="villa, guesthouse, apartment..." />
              </div>
              <div class="col-md-3 align-self-end">
                <button id="searchHotelBtn" class="btn btn-outline-info w-100">Find Hotels</button>
              </div>
            </div>
            <div id="map-hotel" class="mb-3"></div>
            <div id="hotelResults"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    const apiBase = 'api.php';
    let mainMap, crowdMap, foodMap, hotelMap;
    let userMarker = null;
    let placeMarker = null;
    let crowdLayerGroup, foodLayerGroup, hotelLayerGroup;

    function initMaps() {
      const chennai = [13.0827, 80.2707];

      if (!mainMap) {
        mainMap = L.map('map', {zoomControl:true}).setView(chennai, 12);
        crowdMap = L.map('map-crowd').setView(chennai, 13);
        foodMap = L.map('map-food').setView(chennai, 13);
        hotelMap = L.map('map-hotel').setView(chennai, 13);

        [mainMap, crowdMap, foodMap, hotelMap].forEach(m => {
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
          }).addTo(m);
        });

        crowdLayerGroup = L.layerGroup().addTo(crowdMap);
        foodLayerGroup = L.layerGroup().addTo(foodMap);
        hotelLayerGroup = L.layerGroup().addTo(hotelMap);

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(pos => {
            const p = [pos.coords.latitude, pos.coords.longitude];
            userMarker = L.marker(p).addTo(mainMap).bindPopup('Your location').openPopup();
            mainMap.setView(p, 13);
            crowdMap.setView(p, 13);
            foodMap.setView(p, 13);
            hotelMap.setView(p, 13);
          }, ()=>{});
        }
      }
    }

    async function fetchPlace(name) {
      const res = await fetch(`${apiBase}?action=get_place&place=${encodeURIComponent(name)}`);
      return await res.json();
    }
    async function fetchNearbyBest(place_id) {
      const res = await fetch(`${apiBase}?action=get_nearby_best&place_id=${place_id}`);
      return await res.json();
    }
    async function fetchRestaurants(place_id, cuisine, budget) {
      const url = new URL(apiBase, location.href);
      url.searchParams.set('action', 'get_restaurants');
      url.searchParams.set('place_id', place_id);
      if (cuisine) url.searchParams.set('cuisine', cuisine);
      if (budget) url.searchParams.set('budget', budget);
      const res = await fetch(url); return await res.json();
    }
    async function fetchHotels(place_id, stay_type, budget) {
      const url = new URL(apiBase, location.href);
      url.searchParams.set('action', 'get_hotels');
      url.searchParams.set('place_id', place_id);
      if (stay_type) url.searchParams.set('stay_type', stay_type);
      if (budget) url.searchParams.set('budget', budget);
      const res = await fetch(url); return await res.json();
    }

    function showPlaceOnMaps(place) {
      const lat = parseFloat(place.lat), lng = parseFloat(place.lng);
      const pos = [lat, lng];

      if (placeMarker) { try { mainMap.removeLayer(placeMarker); } catch(e){} }
      placeMarker = L.marker(pos, {title: place.name}).addTo(mainMap).bindPopup(place.name);
      mainMap.setView(pos, 14);

      [crowdMap, foodMap, hotelMap].forEach(m => {
        L.marker(pos).addTo(m).bindPopup(place.name);
        m.setView(pos, 13);
      });
    }

    function clearLayer(group) { group.clearLayers(); }

    // ---------------------- CROWD DENSITY ESTIMATION ----------------------
    function estimateDensity(place, visitTime) {
      if (!visitTime) return place.density || 'medium';
      const hour = parseInt(visitTime.split(':')[0]);
      if (hour >= 6 && hour < 10) return 'medium';
      if (hour >= 10 && hour < 16) return 'high';
      if (hour >= 16 && hour < 20) return 'high';
      return 'low';
    }

    function renderOverview(place, nearby) {
      const el = document.getElementById('overviewResults');
      const rList = (nearby.restaurants || []).map(r => `<div class="card result-card"><div class="card-body"><strong>${r.name}</strong> — ${r.cuisine} • ${r.budget}</div></div>`).join('');
      const hList = (nearby.hotels || []).map(h => `<div class="card result-card"><div class="card-body"><strong>${h.name}</strong> — ${h.stay_type} • ${h.budget}</div></div>`).join('');
      el.innerHTML = `
        <h5 style="color:var(--navy); font-weight:700">${place.name} <span class="badge badge-accent">${(place.density||'medium').toUpperCase()}</span></h5>
        <p style="color:var(--navy, #12212E)">${place.description || ''}</p>
        <div class="row">
          <div class="col-md-6"><h6>Nearby Restaurants</h6>${rList||'<p>No restaurants found</p>'}</div>
          <div class="col-md-6"><h6>Nearby Hotels</h6>${hList||'<p>No hotels found</p>'}</div>
        </div>
      `;
    }

    function renderCrowd(place, nearby, visitTime) {
      const el = document.getElementById('crowdResult');
      const density = estimateDensity(place, visitTime);
      const colorClass = density === 'high' ? 'danger' : (density === 'medium' ? 'warning' : 'success');

      clearLayer(crowdLayerGroup);
      (nearby.restaurants || []).forEach(r => {
        L.marker([parseFloat(r.lat), parseFloat(r.lng)])
         .bindPopup(`<strong>${r.name}</strong><br/>${r.cuisine} • ${r.budget}`)
         .addTo(crowdLayerGroup);
      });
      (nearby.hotels || []).forEach(h => {
        L.marker([parseFloat(h.lat), parseFloat(h.lng)])
         .bindPopup(`<strong>${h.name}</strong><br/>${h.stay_type} • ${h.budget}`)
         .addTo(crowdLayerGroup);
      });

      el.innerHTML = `
        <h5 style="font-weight:700">
          Estimated crowd at ${visitTime || 'selected time'}: 
          <span class="badge bg-${colorClass}">${density.toUpperCase()}</span>
        </h5>
        <p>Nearby suggestions shown on the map below.</p>
      `;
    }

    function renderFood(list) {
      const el = document.getElementById('foodResults');
      clearLayer(foodLayerGroup);
      if (!list || list.length === 0) { el.innerHTML = '<p>No restaurants found for these filters.</p>'; return; }
      el.innerHTML = list.map(r => `<div class="card result-card"><div class="card-body"><strong>${r.name}</strong><br/>Cuisine: ${r.cuisine} • Budget: ${r.budget}</div></div>`).join('');
      list.forEach(r => L.marker([parseFloat(r.lat), parseFloat(r.lng)])
        .bindPopup(`${r.name}<br/>${r.cuisine} • ${r.budget}`).addTo(foodLayerGroup));
    }

    function renderHotels(list) {
      const el = document.getElementById('hotelResults');
      clearLayer(hotelLayerGroup);
      if (!list || list.length === 0) { el.innerHTML = '<p>No hotels found for these filters.</p>'; return; }
      el.innerHTML = list.map(h => `<div class="card result-card"><div class="card-body"><strong>${h.name}</strong><br/>Type: ${h.stay_type} • Budget: ${h.budget}</div></div>`).join('');
      list.forEach(h => L.marker([parseFloat(h.lat), parseFloat(h.lng)])
        .bindPopup(`${h.name}<br/>${h.stay_type} • ${h.budget}`).addTo(hotelLayerGroup));
    }

    document.addEventListener('DOMContentLoaded', () => {
      initMaps();

      // Fix Leaflet maps in hidden Bootstrap tabs
      document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabBtn => {
        tabBtn.addEventListener('shown.bs.tab', event => {
          if (event.target.id === 'overview-tab') mainMap.invalidateSize();
          else if (event.target.id === 'crowd-tab') crowdMap.invalidateSize();
          else if (event.target.id === 'food-tab') foodMap.invalidateSize();
          else if (event.target.id === 'hotel-tab') hotelMap.invalidateSize();
        });
      });

      document.getElementById('searchBtn').addEventListener('click', async () => {
        const placeName = document.getElementById('placeInput').value.trim();
        if (!placeName) { alert('Type a place in Chennai (e.g., Marina Beach)'); return; }
        const place = await fetchPlace(placeName);
        if (!place || !place.id) { alert('Place not found in seeded Chennai dataset.'); return; }
        showPlaceOnMaps(place);
        const nearby = await fetchNearbyBest(place.id);
        renderOverview(place, nearby);
      });

      document.getElementById('checkCrowdBtn').addEventListener('click', async () => {
        const placeName = document.getElementById('placeInput').value.trim();
        if (!placeName) { alert('Search a place first'); return; }
        const place = await fetchPlace(placeName);
        const visitTime = document.getElementById('visitTime').value;
        const nearby = await fetchNearbyBest(place.id);
        renderCrowd(place, nearby, visitTime);
        const tab = new bootstrap.Tab(document.getElementById('crowd-tab')); tab.show();
      });

      document.getElementById('searchFoodBtn').addEventListener('click', async () => {
        const placeName = document.getElementById('placeInput').value.trim();
        if (!placeName) { alert('Search a place first'); return; }
        const place = await fetchPlace(placeName);
        const cuisine = document.getElementById('cuisine').value.trim();
        const budget = document.getElementById('foodBudget').value;
        const list = await fetchRestaurants(place.id, cuisine, budget);
        renderFood(list);
        const tab = new bootstrap.Tab(document.getElementById('food-tab')); tab.show();
      });

      document.getElementById('searchHotelBtn').addEventListener('click', async () => {
        const placeName = document.getElementById('placeInput').value.trim();
        if (!placeName) { alert('Search a place first'); return; }
        const place = await fetchPlace(placeName);
        const stay = document.getElementById('stayType').value.trim();
        const budget = document.getElementById('hotelBudget').value;
        const list = await fetchHotels(place.id, stay, budget);
        renderHotels(list);
        const tab = new bootstrap.Tab(document.getElementById('hotel-tab')); tab.show();
      });
    });
  </script>
</body>
</html>

