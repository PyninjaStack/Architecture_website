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
    projectList.innerHTML = "";

    const dataSet = showingAll ? allProjects : allProjects.filter(p => p.selected);
    const grouped = groupByFilter(dataSet, filterType);
    const wrapper = document.createElement("div");
    wrapper.className = "horizontal-group-wrapper-static";

    const sortedKeys = Object.keys(grouped).sort((a, b) => {
      if (!isNaN(a) && !isNaN(b)) return a - b;
      return a.localeCompare(b);
    });

    // 🔧 Apply dense layout class based on group count
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

    projectList.appendChild(wrapper);
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
        if (s < 1000) key = "< 1K m²";
        else if (s < 10000) key = "1K–10K m²";
        else key = "> 10K m²";
      } else if (type === "status") key = p.status || "Unknown";
      else if (type === "location") key = p.location || "Unknown";
      else key = "All";

      if (!groups[key]) groups[key] = [];
      groups[key].push(p);
    });

    return groups;
  }

  function createCard(project) {
    const div = document.createElement("div");
    div.className = "project-card";

    const img = document.createElement("img");
    img.src = project.thumbnail || `assets/images/${project.image}`;
    img.alt = project.title;

    const title = document.createElement("div");
    title.className = "project-title";
    title.textContent = project.title;

    div.appendChild(img);
    div.appendChild(title);

    return div;
  }
});
