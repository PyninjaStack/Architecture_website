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
      if (filterType === "status") {
        const order = ["Idea", "In Progress", "UnderConstruction", "Completed"];
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

  /*function createCard(project) {
    const wrapper = document.createElement("div");
    wrapper.className = "project-card-wrapper";

    const card = document.createElement("div");
    card.className = "project-card";

    const img = document.createElement("img");
    img.src = project.thumbnail || `assets/images/${project.image}`;
    img.alt = project.title;

    const tooltip = document.createElement("div");
    tooltip.className = "project-tooltip";
    tooltip.textContent = `${project.code} - ${project.title}`;
    wrapper.appendChild(tooltip);

    // 🧠 Track mouse to show tooltip above cursor
    card.addEventListener("mousemove", e => {
      tooltip.style.opacity = "1";
      tooltip.style.left = `${e.offsetX + 10}px`;
      tooltip.style.top = `${e.offsetY - 25}px`; // 👆 Show above the image
    });

    card.addEventListener("mouseleave", () => {
      tooltip.style.opacity = "0";
    });

    card.appendChild(img);
    wrapper.appendChild(card);
    return wrapper;
  }*/
  /*function createCard(project) {
  const wrapper = document.createElement("div");
  wrapper.className = "project-card-wrapper";

  const card = document.createElement("div");
  card.className = "project-card";

  const img = document.createElement("img");
  img.src = project.thumbnail || `assets/images/${project.image}`;
  img.alt = project.title;

  // Tooltip for hover effect
  const tooltip = document.createElement("div");
  tooltip.className = "project-tooltip";
  tooltip.textContent = `${project.code} - ${project.title}`;
  wrapper.appendChild(tooltip);

  // 👇 Always-visible project code below the image
  const codeLabel = document.createElement("div");
  codeLabel.className = "project-code-label";
  codeLabel.textContent = project.code;
  wrapper.appendChild(codeLabel);

  // 🧠 Cursor tracking for tooltip
  card.addEventListener("mousemove", e => {
    tooltip.style.opacity = "1";
    tooltip.style.left = `${e.offsetX + 10}px`;
    tooltip.style.top = `${e.offsetY - 30}px`;
  });

  card.addEventListener("mouseleave", () => {
    tooltip.style.opacity = "0";
  });

  card.appendChild(img);
  wrapper.appendChild(card);
  return wrapper;
}*/

function createCard(project) {
  const wrapper = document.createElement("a");
  wrapper.className = "project-card-wrapper";
  wrapper.href = `/Positive Space Design Studio/pages/projects.php?code=${project.code}`;

  const card = document.createElement("div");
  card.className = "project-card";

  const img = document.createElement("img");
  img.src = project.thumbnail || `assets/images/${project.image}`;
  img.alt = project.title;

  // Tooltip for hover effect
  const tooltip = document.createElement("div");
  tooltip.className = "project-tooltip";
  tooltip.textContent = `${project.code} - ${project.title}`;
  wrapper.appendChild(tooltip);

  // Append image inside card
  card.appendChild(img);
  wrapper.appendChild(card);

  // ✅ Now append code label BELOW the image
  const codeLabel = document.createElement("div");
  codeLabel.className = "project-code-label";
  codeLabel.textContent = project.code;
  wrapper.appendChild(codeLabel);

  // Mouse tracking
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


});
