window.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filter-button[data-filter]");
  const toggleButton = document.getElementById("toggle-projects");
  const projectList = document.getElementById("project-list");

  let allProjects = [];
  let showingAll = false;

  fetch("data/projects.json")
    .then(res => res.json())
    .then(data => {
      allProjects = data;
      renderProjects("chronological");
    });

  filterButtons.forEach(button => {
    button.addEventListener("click", () => {
      document.querySelector(".filter-button.active")?.classList.remove("active");
      button.classList.add("active");
      renderProjects(button.dataset.filter);
    });
  });

  if (toggleButton) {
    toggleButton.addEventListener("click", () => {
      showingAll = !showingAll;
      toggleButton.textContent = showingAll ? "SELECTED PROJECTS" : "ALL PROJECTS";
      document.querySelector(".filter-button.active")?.classList.remove("active");
      const chronoButton = document.querySelector(".filter-button[data-filter='chronological']");
      chronoButton.classList.add("active");
      renderProjects("chronological");
    });
  }

  function renderProjects(filterType) {
    const dataSet = showingAll ? allProjects : allProjects.filter(p => p.selected);

    // ✅ Special case: show world map when filter = location
    if (filterType === "location") {
      projectList.innerHTML = `<div id="map-plotly" style="width:100%; height:550px;"></div>`;
      renderLocationMap(dataSet);
      return;
    }

    const grouped = groupByFilter(dataSet, filterType);

    const oldCards = Array.from(projectList.querySelectorAll(".project-card-wrapper"));
    const firstRects = new Map();
    oldCards.forEach(card => {
      const rect = card.getBoundingClientRect();
      firstRects.set(card.dataset.code, rect);
    });

    const wrapper = document.createElement("div");
    wrapper.className = "horizontal-group-wrapper-static";

    const sortedKeys = Object.keys(grouped).sort((a, b) => {
      if (filterType === "status") {
        const order = ["Idea", "In Progress", "UnderConstruction", "Completed"];
        return order.indexOf(a) - order.indexOf(b);
      }
      if (filterType === "programmatic") {
        const order = ["Residential", "Commercial Housing", "Commercial", "Educational", "Religious", "Hotel", "Industrial", "Warehouse", "Urban Development", "Interior", "Landscape"]; 
        return order.indexOf(a) - order.indexOf(b);
      }
      if (filterType === "scale") {
        const order = ["< 100 m²", "100–300 m²", "301–500 m²", "501–1000 m²", "1001–5000 m²", "5001–10000 m²", "> 10000 m²"]; 
        return order.indexOf(a) - order.indexOf(b);
      }
      if (!isNaN(a) && !isNaN(b)) return a - b;
      return a.localeCompare(b);
    });

    if (sortedKeys.length > 12) {
      wrapper.classList.add("dense-layout");
    }

    sortedKeys.forEach(key => {
      const group = document.createElement("div");
      group.className = "group-block-static";

      const stack = document.createElement("div");
      stack.className = "group-stack-static";

      grouped[key].forEach(project => {
        const card = createCard(project);
        stack.appendChild(card);
      });

      const label = document.createElement("div");
      label.className = "group-label-bottom-static";
      label.textContent = key;

      group.appendChild(stack);
      group.appendChild(label);
      wrapper.appendChild(group);
    });

    // Replace old with new DOM
    projectList.innerHTML = "";
    projectList.appendChild(wrapper);

    // ✅ Resize logic
    let maxGroupSize = 0;
    wrapper.querySelectorAll(".group-stack-static").forEach(group => {
      const count = group.querySelectorAll(".project-card-wrapper").length;
      if (count > maxGroupSize) maxGroupSize = count;
    });

    let shrinkClass = "";
    if (maxGroupSize > 12 && maxGroupSize <= 16) shrinkClass = "shrink-1";
    else if (maxGroupSize > 16 && maxGroupSize <= 20) shrinkClass = "shrink-2";
    else if (maxGroupSize > 20 && maxGroupSize <= 24) shrinkClass = "shrink-3";
    else if (maxGroupSize > 24) shrinkClass = "shrink-4";

    wrapper.querySelectorAll(".group-stack-static").forEach(group => {
      group.classList.remove("shrink-1", "shrink-2", "shrink-3", "shrink-4");
      if (shrinkClass) group.classList.add(shrinkClass);
    });

    // Animate FLIP
    const newCards = wrapper.querySelectorAll(".project-card-wrapper");
    newCards.forEach(card => {
      const code = card.dataset.code;
      const first = firstRects.get(code);
      const last = card.getBoundingClientRect();

      if (first) {
        const deltaX = first.left - last.left;
        const deltaY = first.top - last.top;

        card.animate(
          [
            { transform: `translate(${deltaX}px, ${deltaY}px)` },
            { transform: "translate(0, 0)" }
          ],
          { duration: 400, easing: "ease" }
        );
      }
    });
  }

  function groupByFilter(projects, type) {
    const groups = {};
    projects.forEach(p => {
      let key = "";
      if (type === "chronological") key = p.year || "Unknown";
      else if (type === "alphabetical") key = /^[A-Za-z]/.test(p.title) ? p.title[0].toUpperCase() : "#";
      else if (type === "programmatic") key = p.category || "Unknown";
      else if (type === "scale") {
        const s = parseInt(p.scale) || 0;
        if (s < 100) key = "< 100 m²";
        else if (s >= 100 && s <= 300) key = "100–300 m²";
        else if (s >= 301 && s <= 500) key = "301–500 m²";
        else if (s >= 501 && s <= 1000) key = "501–1000 m²";
        else if (s >= 1001 && s <= 5000) key = "1001–5000 m²";
        else if (s >= 5001 && s <= 10000) key = "5001–10000 m²";
        else key = "> 10000 m²";
      } else if (type === "status") key = p.status || "Unknown";
      else if (type === "location") key = p.location || "Unknown";
      else key = "All";

      if (!groups[key]) groups[key] = [];
      groups[key].push(p);
    });
    return groups;
  }

  function createCard(project) {
    const wrapper = document.createElement("a");
    wrapper.className = "project-card-wrapper";
    const currentFilter = document.querySelector(".filter-button.active")?.dataset.filter;
    const anchor = (currentFilter === "location") ? "#map-section" : "";
    wrapper.href = `/Positive Space Design Studio/pages/projects.php?code=${project.code}${anchor}`;
    wrapper.dataset.code = project.code;

    const card = document.createElement("div");
    card.className = "project-card";

    const img = document.createElement("img");
    img.src = project.thumbnail || `assets/images/${project.image}`;
    img.alt = project.title;

    const tooltip = document.createElement("div");
    tooltip.className = "project-tooltip";
    tooltip.textContent = `${project.code} - ${project.title}`;
    wrapper.appendChild(tooltip);

    card.appendChild(img);
    wrapper.appendChild(card);

    const codeLabel = document.createElement("div");
    codeLabel.className = "project-code-label";
    codeLabel.textContent = project.code;
    wrapper.appendChild(codeLabel);

    card.addEventListener("mousemove", e => {
      tooltip.style.opacity = "1";
      tooltip.style.left = `${e.offsetX + 10}px`;
      tooltip.style.top = `${e.offsetY - 30}px`;
    });

    card.addEventListener("mouseleave", () => {
      tooltip.style.opacity = "0";
    });

    return wrapper;
  }

  // ✅ Function to draw Plotly world map
  function renderLocationMap(projects) {
  const container = document.getElementById("map-plotly");
  if (!container) return;

  container.style.display = "block"; // make sure it shows

  const lats = projects.map(p => p.latitude);
  const lons = projects.map(p => p.longitude);
  const names = projects.map(p => p.title);

  const data = [{
    type: "scattergeo",
    mode: "markers",
    text: names,
    lat: lats,
    lon: lons,
    marker: {
      size: 10,
      color: "blue",
      line: { width: 1, color: "white" }
    }
  }];

  const layout = {
    geo: {
      scope: "world",
      showland: true,
      landcolor: "rgb(230,230,230)",
      countrycolor: "rgb(200,200,200)"
    },
    margin: { t: 0, b: 0, l: 0, r: 0 }
  };

  Plotly.newPlot("map-plotly", data, layout, { responsive: true });
}

function hideLocationMap() {
  const container = document.getElementById("map-plotly");
  if (container) {
    container.style.display = "none";   // ✅ hide completely
  }
}

});

  document.querySelectorAll(".share-icon").forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      const shareUrl = this.dataset.shareUrl;

      if (navigator.share) {
        navigator.share({
          title: "Check this out",
          url: shareUrl
        });
      } else {
        window.open(`https://wa.me/?text=${encodeURIComponent(shareUrl)}`, "_blank");
      }
    });
  });


document.addEventListener("DOMContentLoaded", () => {
  // Like button
  document.querySelectorAll(".like-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      fetch(`/Positive Space Design Studio/actions/like.php?id=${id}&type=like`)
        .then(res => res.json())
        .then(data => {
          document.getElementById(`like-count-${id}`).innerText = data.likes;
          document.getElementById(`dislike-count-${id}`).innerText = data.dislikes;
        });
    });
  });

  // Dislike button
  document.querySelectorAll(".dislike-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      fetch(`/Positive Space Design Studio/actions/like.php?id=${id}&type=dislike`)
        .then(res => res.json())
        .then(data => {
          document.getElementById(`like-count-${id}`).innerText = data.likes;
          document.getElementById(`dislike-count-${id}`).innerText = data.dislikes;
        });
    });
  });

  // Share button
  document.querySelectorAll(".share-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const url = btn.dataset.url;
      if (navigator.share) {
        navigator.share({
          title: "Check this project!",
          url: url
        });
      }
    });
  });
});
