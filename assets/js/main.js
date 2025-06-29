// assets/js/main.js

/*document.addEventListener("DOMContentLoaded", () => {
  const projectList = document.getElementById("project-list");
  const filterButtons = document.querySelectorAll(".filters button");
  let projects = [];

  // Fetch projects from JSON
  fetch("data/projects.json")
    .then(res => res.json())
    .then(data => {
      projects = data;
      renderProjects(projects); // default = chronological
    });

  // Filter buttons
  filterButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      filterButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      const type = btn.getAttribute("data-filter");
      let sorted = [...projects];

      switch (type) {
        case "alphabetical":
          sorted.sort((a, b) => a.title.localeCompare(b.title));
          break;
        case "programmatic":
          sorted.sort((a, b) => a.category.localeCompare(b.category));
          break;
        case "scale":
          sorted.sort((a, b) => a.scale.localeCompare(b.scale));
          break;
        case "status":
          sorted.sort((a, b) => a.status.localeCompare(b.status));
          break;
        case "location":
          sorted.sort((a, b) => a.location.localeCompare(b.location));
          break;
        case "chronological":
          sorted.sort((a, b) => a.year - b.year);
          break;
        case "all":
        default:
          // no sorting
          break;
      }

      renderProjects(sorted);
    });
  });

  function renderProjects(list) {
    projectList.innerHTML = "";
    list.forEach(project => {
      const card = document.createElement("div");
      card.className = "project-card";
      card.innerHTML = `
        <img src="assets/images/${project.image}" alt="${project.title}">
        <h3>${project.code}: ${project.title}</h3>
        <p><strong>Year:</strong> ${project.year}</p>
        <p><strong>Category:</strong> ${project.category}</p>
        <p><strong>Status:</strong> ${project.status}</p>
        <p><strong>Scale:</strong> ${project.scale}</p>
        <p><strong>Location:</strong> ${project.location}</p>
      `;
      projectList.appendChild(card);
    });
  }
}); */


/*document.addEventListener("DOMContentLoaded", function () {
  const projectContainer = document.getElementById("project-container");

  // Dummy data — replace with your actual data
  const projects = [
    {
      name: "VMU",
      icon: "assets/images/vmu-icon.jpg",
      preview: "assets/images/vmu-preview.jpg",
      code: "vmu"
    },
    {
      name: "KIRK",
      icon: "assets/images/kirk-icon.jpg",
      preview: "assets/images/kirk-preview.jpg",
      code: "kirk"
    },
    {
      name: "ABC",
      icon: "assets/images/abc-icon.jpg",
      preview: "assets/images/abc-preview.jpg",
      code: "abc"
    }
  ];

  projects.forEach((project) => {
    const card = document.createElement("a");
    card.href = `?projectcode=${project.code}`;
    card.classList.add("project-card");

    card.innerHTML = `
      <div class="project-thumbnail">
        <img src="${project.icon}" alt="${project.name} Icon">
      </div>
      <div class="project-hover">
        <img src="${project.preview}" alt="${project.name} Preview">
        <span class="project-title">${project.name}</span>
      </div>
    `;

    projectContainer.appendChild(card);
  });
}); */

// Add the following updated JavaScript for dynamic project grouping and filtering

// Wait for DOM
/* window.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filters button");
  const projectList = document.getElementById("project-list");
  let allProjects = [];

  // Fetch data
  fetch("data/projects.json")
    .then(res => res.json())
    .then(data => {
      allProjects = data;
      renderProjects("chronological");
    });

  // Filter event
  filterButtons.forEach(button => {
    button.addEventListener("click", () => {
      document.querySelector(".filters .active")?.classList.remove("active");
      button.classList.add("active");
      renderProjects(button.dataset.filter);
    });
  });

  function renderProjects(filterType) {
    projectList.innerHTML = "";
    const grouped = groupByFilter(filterType);

    const layout = document.createElement("div");
    layout.className = "grouping-container";

    for (const key in grouped) {
      const column = document.createElement("div");
      column.className = "group-column";

      const label = document.createElement("div");
      label.className = "group-label";
      label.textContent = key;
      column.appendChild(label);

      grouped[key].forEach(project => {
        const card = createCard(project);
        column.appendChild(card);
      });

      layout.appendChild(column);
    }

    projectList.appendChild(layout);
  }

  function groupByFilter(type) {
    const groups = {};

    allProjects.forEach(p => {
      let key = "";
      if (type === "chronological") key = p.year || "Unknown";
      else if (type === "alphabetical") key = /^[A-Za-z]/.test(p.title) ? p.title[0].toUpperCase() : "#";
      else if (type === "programmatic") key = p.status || "Unknown";
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
    img.src = project.thumbnail;
    img.alt = project.title;

    const title = document.createElement("div");
    title.className = "project-title";
    title.textContent = project.title;

    div.appendChild(img);
    div.appendChild(title);

    return div;
  }
}); */

/* window.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filters button");
  const projectList = document.getElementById("project-list");
  let allProjects = [];

  // Fetch data
  fetch("data/projects.json")
    .then(res => res.json())
    .then(data => {
      allProjects = data;
      renderProjects("chronological");
    });

  // Filter click event
  filterButtons.forEach(button => {
    button.addEventListener("click", () => {
      document.querySelector(".filters .active")?.classList.remove("active");
      button.classList.add("active");
      renderProjects(button.dataset.filter);
    });
  });

  function renderProjects(filterType) {
    projectList.innerHTML = "";
    const grouped = groupByFilter(filterType);

    const wrapper = document.createElement("div");
    wrapper.className = "horizontal-group-wrapper";

    for (const key in grouped) {
      const group = document.createElement("div");
      group.className = "group-block";

      const stack = document.createElement("div");
      stack.className = "group-stack";

      grouped[key].forEach(project => {
        const card = createCard(project);
        stack.appendChild(card);
      });

      const label = document.createElement("div");
      label.className = "group-label-bottom";
      label.textContent = key;

      group.appendChild(stack);
      group.appendChild(label);
      wrapper.appendChild(group);
    }

    projectList.appendChild(wrapper);
  }

  function groupByFilter(type) {
    const groups = {};

    allProjects.forEach(p => {
      let key = "";
      if (type === "chronological") key = p.year || "Unknown";
      else if (type === "alphabetical") key = /^[A-Za-z]/.test(p.title) ? p.title[0].toUpperCase() : "#";
      else if (type === "programmatic") key = p.status || "Unknown";
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
    img.src = project.thumbnail;
    img.alt = project.title;

    const title = document.createElement("div");
    title.className = "project-title";
    title.textContent = project.title;

    div.appendChild(img);
    div.appendChild(title);

    return div;
  }
}); */

/*window.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filters button");
  const projectList = document.getElementById("project-list");
  let allProjects = [];

  // Fetch data
  fetch("data/projects.json")
    .then(res => res.json())
    .then(data => {
      allProjects = data;
      renderProjects("chronological");
    });

  // Filter click event
  filterButtons.forEach(button => {
    button.addEventListener("click", () => {
      document.querySelector(".filters .active")?.classList.remove("active");
      button.classList.add("active");
      renderProjects(button.dataset.filter);
    });
  });

  function renderProjects(filterType) {
    projectList.innerHTML = "";
    const grouped = groupByFilter(filterType);

    const wrapper = document.createElement("div");
    wrapper.className = "horizontal-group-wrapper-static";

    for (const key in grouped) {
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
    }

    projectList.appendChild(wrapper);
  }

  function groupByFilter(type) {
    const groups = {};

    allProjects.forEach(p => {
      let key = "";
      if (type === "chronological") key = p.year || "Unknown";
      else if (type === "alphabetical") key = /^[A-Za-z]/.test(p.title) ? p.title[0].toUpperCase() : "#";
      else if (type === "programmatic") key = p.status || "Unknown";
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
    img.src = project.thumbnail;
    img.alt = project.title;

    const title = document.createElement("div");
    title.className = "project-title";
    title.textContent = project.title;

    div.appendChild(img);
    div.appendChild(title);

    return div;
  }
}); */

window.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filters button");
  const projectList = document.getElementById("project-list");
  let allProjects = [];

  // Fetch data
  fetch("data/projects.json")
    .then(res => res.json())
    .then(data => {
      allProjects = data;
      renderProjects("chronological");
    });

  // Filter click event
  filterButtons.forEach(button => {
    button.addEventListener("click", () => {
      document.querySelector(".filters .active")?.classList.remove("active");
      button.classList.add("active");
      renderProjects(button.dataset.filter);
    });
  });

  /*function renderProjects(filterType) {
    projectList.innerHTML = "";
    const grouped = groupByFilter(filterType);

    const wrapper = document.createElement("div");
    wrapper.className = "horizontal-group-wrapper-static";

    const sortedKeys = Object.keys(grouped).sort((a, b) => {
      // Chronological and Scale sorting numerically or by custom logic
      if (filterType === "chronological") return parseInt(a) - parseInt(b);
      if (filterType === "alphabetical") return a.localeCompare(b);
      if (filterType === "scale") {
        const scaleOrder = { "< 1K m²": 1, "1K–10K m²": 2, "> 10K m²": 3 };
        return scaleOrder[a] - scaleOrder[b];
      }
      return a.localeCompare(b);
    });

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
  }*/

     function renderProjects(filterType) {
     projectList.innerHTML = "";
     const grouped = groupByFilter(filterType);

     const wrapper = document.createElement("div");
     wrapper.className = "horizontal-group-wrapper-static";

    // Sort keys
      const sortedKeys = Object.keys(grouped).sort((a, b) => {
      if (!isNaN(a) && !isNaN(b)) return a - b;  // Year sort
      return a.localeCompare(b);                // Alphabetical
    });

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


      function groupByFilter(type) {
      const groups = {};

      allProjects.forEach(p => {
        let key = "";
        if (type === "chronological") key = p.year || "Unknown";
        else if (type === "alphabetical") key = /^[A-Za-z]/.test(p.title) ? p.title[0].toUpperCase() : "#";
        else if (type === "programmatic") key = p.status || "Unknown";
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
      img.src = project.thumbnail;
      img.alt = project.title;

      const title = document.createElement("div");
      title.className = "project-title";
      title.textContent = project.title;

      div.appendChild(img);
      div.appendChild(title);

      return div;
    }
});
