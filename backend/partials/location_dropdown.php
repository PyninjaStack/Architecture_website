<?php
function renderLocationDropdown($selected = "") {
    ?>
    <div class="form-row">
        <div class="form-group">
            <label>Country</label>
            <select id="country" onchange="updateStates()" required>
                <option value="">Select Country</option>
            </select>
        </div>
        <div class="form-group">
            <label>State</label>
            <select id="state" onchange="updateCities()" required>
                <option value="">Select State</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>City</label>
            <select id="city" onchange="finalizeLocation()" required>
                <option value="">Select City</option>
            </select>
        </div>
    </div>
    <input type="hidden" name="location" id="location" value="<?= htmlspecialchars($selected) ?>">
    
    <script>
    async function loadCountries() {
        let res = await fetch("https://countriesnow.space/api/v0.1/countries/positions");
        let data = await res.json();
        let select = document.getElementById("country");
        data.data.forEach(c => {
            let opt = document.createElement("option");
            opt.value = c.name;
            opt.textContent = c.name;
            if ("<?= $selected ?>" && "<?= $selected ?>".includes(c.name)) opt.selected = true;
            select.appendChild(opt);
        });
    }

    async function updateStates() {
        let country = document.getElementById("country").value;
        let stateSelect = document.getElementById("state");
        stateSelect.innerHTML = "<option value=''>Select State</option>";
        if (!country) return;
        let res = await fetch("https://countriesnow.space/api/v0.1/countries/states", {
            method: "POST", headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ country })
        });
        let data = await res.json();
        data.data.states.forEach(s => {
            let opt = document.createElement("option");
            opt.value = s.name;
            opt.textContent = s.name;
            if ("<?= $selected ?>" && "<?= $selected ?>".includes(s.name)) opt.selected = true;
            stateSelect.appendChild(opt);
        });
    }

    async function updateCities() {
        let country = document.getElementById("country").value;
        let state = document.getElementById("state").value;
        let citySelect = document.getElementById("city");
        citySelect.innerHTML = "<option value=''>Select City</option>";
        if (!country || !state) return;
        let res = await fetch("https://countriesnow.space/api/v0.1/countries/state/cities", {
            method: "POST", headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ country, state })
        });
        let data = await res.json();
        data.data.forEach(city => {
            let opt = document.createElement("option");
            opt.value = city;
            opt.textContent = city;
            if ("<?= $selected ?>" && "<?= $selected ?>".includes(city)) opt.selected = true;
            citySelect.appendChild(opt);
        });
    }

    function finalizeLocation() {
        const country = document.getElementById("country").value;
        const state = document.getElementById("state").value;
        const city = document.getElementById("city").value;
        document.getElementById("location").value = [country,state,city].filter(Boolean).join(", ");
    }

    window.onload = loadCountries;
    </script>
    <?php
}
