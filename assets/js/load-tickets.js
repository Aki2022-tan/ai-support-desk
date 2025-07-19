// Wait for the DOM to fully load before executing the script
document.addEventListener("DOMContentLoaded", function () {
  // Retrieve key DOM elements
  const searchInput = document.getElementById("searchInput");
  const ticketContainer = document.getElementById("ticketContainer");
  const tabs = document.querySelectorAll(".ticket-tab");

  // Define initial state variables
  let page = 1;           // Current page for pagination
  let loading = false;    // Prevents concurrent loading
  let tab = "ongoing";    // Active tab: 'ongoing' or 'resolved'
  let search = "";        // Current search keyword

  /**
   * Loads ticket entries from the server via AJAX.
   * @param {boolean} reset - Indicates if the list should be reset (new search or tab switch).
   */
  function loadTickets(reset = false) {
    if (loading) return; // Abort if a request is already in progress
    loading = true;

    // Reset state if necessary
    if (reset) {
      page = 1;
      ticketContainer.innerHTML = ""; // Clear current ticket list
    }

    // Prepare form data for request
    const formData = new FormData();
    formData.append("page", page);
    formData.append("search", search);

    // Determine the appropriate endpoint based on active tab
    const url =
      tab === "ongoing"
        ? "ajax/fetch-ongoing-tickets.php"
        : "ajax/fetch-resolved-tickets.php";

    // Perform asynchronous fetch request
    fetch(url, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((data) => {
        // Append results if data is returned
        if (data.trim()) {
          ticketContainer.insertAdjacentHTML("beforeend", data);
          page++; // Increment page for next request
        }
        loading = false;
      })
      .catch(() => {
        console.error("Failed to load tickets.");
        loading = false;
      });
  }

  /**
   * Creates a debounced version of a function to prevent excessive firing.
   * @param {Function} fn - The original function to debounce.
   * @param {number} delay - Delay in milliseconds.
   * @returns {Function} Debounced function.
   */
  function debounce(fn, delay) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn.apply(this, args), delay);
    };
  }

  // Attach debounced search functionality
  const debouncedSearch = debounce((e) => {
    search = e.target.value.trim();
    loadTickets(true); // Reload tickets based on search input
  }, 300);

  searchInput.addEventListener("input", debouncedSearch);

  /**
   * Creates a throttled version of a function to limit how often it's invoked.
   * @param {Function} fn - The original function to throttle.
   * @param {number} limit - Minimum interval in milliseconds between invocations.
   * @returns {Function} Throttled function.
   */
  function throttle(fn, limit) {
    let inThrottle;
    return function () {
      if (!inThrottle) {
        fn(); // Execute the function
        inThrottle = true;
        setTimeout(() => (inThrottle = false), limit); // Reset throttle state
      }
    };
  }

  // Attach scroll event listener with throttling to support infinite scrolling
  window.addEventListener(
    "scroll",
    throttle(() => {
      // Trigger ticket loading when nearing the bottom of the page
      if (
        window.innerHeight + window.scrollY >=
        document.body.offsetHeight - 100
      ) {
        loadTickets();
      }
    }, 300)
  );

  // Attach click handlers to tab buttons to switch ticket views
  tabs.forEach((t) => {
    t.addEventListener("click", () => {
      // Remove 'active' class from all tabs and apply it to the clicked one
      tabs.forEach((tab) => tab.classList.remove("active"));
      t.classList.add("active");

      // Update active tab and refresh ticket list
      tab = t.dataset.tab;
      loadTickets(true);
    });
  });

  // Initial load of tickets when page is ready
  loadTickets(true);
});